<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr;


use Amplifr\Accounts\AccountInterface;
use Amplifr\Attachments\Video;
use Amplifr\Exceptions\AmplifrException;
use Amplifr\Exceptions\IoAmplifrException;
use Amplifr\Exceptions\ApiAmplifrException;
use Amplifr\Accounts\Account;
use Amplifr\Attachments\Image;
use Amplifr\Attachments\AttachmentInterface;
use Amplifr\Posts\Post;
use Amplifr\Projects\Project;
use Amplifr\Users\User;
use Amplifr\Posts\DraftInterface;

use Amplifr\Stat\StatPublication;
use Amplifr\Stat\StatReport;
use Amplifr\Stat\StatReportInterface;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

/**
 * Class Amplifr
 * @package Amplifr
 */
class Amplifr implements AmplifrInterface
{
    /**
     * @var string api endpoint
     */
    const API_ENDPOINT = 'https://amplifr.com/api/v1';

    /**
     * @var string oauth endpoint
     */
    const OAUTH_ENDPOINT = 'https://amplifr.com/oauth';

    /**
     * @var string SDK version
     */
    const SDK_VERSION = '1.1.0';

    /**
     * @var string user agent
     */
    const API_USER_AGENT = 'amplifr-php';

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @see https://github.com/Seldaek/monolog
     * @var LoggerInterface PSR-3 compatible logger, use only from wrappers methods log*
     */
    protected $log;

    /**
     * @var array raw request, contain all cURL options array and API query
     */
    protected $rawRequest;

    /**
     * @var array request info data structure акщь curl_getinfo function
     */
    protected $requestInfo;

    /**
     * @var array custom options for cURL
     */
    protected $customCurlOptions;

    /**
     * @var array default options for cURL
     */
    protected $defaultCurlOptions;

    /**
     * @var string raw response from Amplifr
     */
    protected $rawResponse;

    /**
     * get client access grant url
     *
     * @param $clientId string
     * @param $redirectUri string
     *
     * @return string
     *
     * @throws ApiAmplifrException
     */
    public function getClientAccessGrantUrl($clientId, $redirectUri)
    {
        if ('' === (string)$clientId) {
            $errorMessage = 'client id is empty';
            $this->log->error($errorMessage);
            throw new ApiAmplifrException($errorMessage);
        }

        if ('' === (string)$redirectUri) {
            $errorMessage = 'redirect uri is empty';
            $this->log->error($errorMessage);
            throw new ApiAmplifrException($errorMessage);
        }

        $clientAccessGrantUrl = sprintf('%s/authorize/?client_id=%s&response_type=code&redirect_uri=%s',
            self::OAUTH_ENDPOINT, $clientId, $redirectUri);

        $this->log->debug(sprintf('client access grant URL: %s', $clientAccessGrantUrl), array(
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'client_access_grant_url' => $clientAccessGrantUrl
        ));

        return $clientAccessGrantUrl;
    }

    /**
     * get client access token
     *
     * @param $userAccessGrant string
     * @param $clientId string
     * @param $clientSecret string
     * @param $defaultRedirectUri string
     *
     * @throws IoAmplifrException
     * @throws AmplifrException
     * @throws ApiAmplifrException
     *
     * @return array
     */
    public function getClientAccessToken($userAccessGrant, $clientId, $clientSecret, $defaultRedirectUri)
    {
        if ('' === (string)$userAccessGrant) {
            $errorMessage = 'user access grant id is empty';
            $this->log->error($errorMessage);
            throw new ApiAmplifrException($errorMessage);
        }

        if ('' === (string)$clientId) {
            $errorMessage = 'client id is empty';
            $this->log->error($errorMessage);
            throw new ApiAmplifrException($errorMessage);
        }

        if ('' === (string)$clientSecret) {
            $errorMessage = 'client secret is empty';
            $this->log->error($errorMessage);
            throw new ApiAmplifrException($errorMessage);
        }

        if ('' === (string)$defaultRedirectUri) {
            $errorMessage = 'default redirect uri is empty';
            $this->log->error($errorMessage);
            throw new ApiAmplifrException($errorMessage);
        }

        $url = sprintf('%s/token/?code=%s&client_id=%s&client_secret=%s&grant_type=authorization_code&redirect_uri=%s',
            self::OAUTH_ENDPOINT, $userAccessGrant, $clientId, $clientSecret, $defaultRedirectUri);

        $curlResult = $this->curlWrapper(
            array(
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Cache-Control: no-cache',
                    'X-ENVIRONMENT-PHP-VERSION: ' . phpversion()
                ),
                CURLOPT_URL => $url
            )
        );
        $arResult = $this->decodeApiJsonResponse($curlResult);
        // handle auth errors
        if (array_key_exists('error', $arResult)) {
            $errorMessage = sprintf('oauth error [%s] with message [%s]', $arResult['error'],
                $arResult['error_description']);
            $this->log->error($errorMessage, array(
                'error' => $arResult['error'],
                'error_description' => $arResult['error_description'],
                'context' => $this->getContext()
            ));
            throw new ApiAmplifrException($errorMessage);
        }
        $this->log->debug(sprintf('access token: %s', $arResult['access_token']), $arResult);
        return $arResult;
    }

    /**
     * Amplifr constructor.
     * @param LoggerInterface|null $obLogger
     * @throws  AmplifrException
     */
    public function __construct(LoggerInterface $obLogger = null)
    {
        $this->setDefaultCurlOptions(array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_USERAGENT => strtolower(self::API_USER_AGENT . '-v' . self::SDK_VERSION),
        ));

        if ($obLogger !== null) {
            $this->log = $obLogger;
        } else {
            $this->log = new NullLogger();
        }
        $this->log->debug('init Amplifr API wrapper');
    }

    /**
     * get context
     *
     * @return array
     */
    protected function getContext()
    {
        return array(
            //user settings
            'access_token' => $this->getAccessToken(),
            // API
            'curl_request_info' => $this->getRequestInfo(),
            'raw_request' => $this->getRawRequest(),
            'raw_response' => $this->getRawResponse(),
            // environment
            'php_version' => phpversion()
        );
    }

    /**
     * @return string
     */
    protected function getAccessToken()
    {
        return (string)$this->accessToken;
    }

    /**
     * @param $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = (string)$accessToken;
    }

    /**
     * Return raw request, contain all cURL options array and API query. Data available after you try to call method call
     * numbers of array keys is const of cURL module. Example: CURLOPT_RETURNTRANSFER = 19913
     *
     * @return array | null
     */
    public function getRawRequest()
    {
        return $this->rawRequest;
    }

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * Return result from function curl_getinfo. Data available after you try to call domain specific method
     *
     * @return array | null
     */
    public function getRequestInfo()
    {
        return $this->requestInfo;
    }

    /**
     * @return \SplObjectStorage of Project
     * @throws AmplifrException
     */
    public function getProjects()
    {
        $arResult = $this->executeApiRequest('/projects', 'GET', array());

        $obCollection = new \SplObjectStorage();
        foreach ($arResult['result']['projects'] as $cnt => $arItemProject) {
            $obCollection->attach(new Project($arItemProject));
        }
        return $obCollection;
    }

    /**
     * get all accounts
     * @param $projectId
     * @return \SplObjectStorage of Accounts
     * @throws AmplifrException
     */
    public function getAccounts($projectId)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/accounts', $projectId), 'GET', array());
        $obCollection = new \SplObjectStorage();
        foreach ($arResult['result']['accounts'] as $cnt => $arItemAccount) {
            $obCollection->attach(new Account($arItemAccount));
        }
        return $obCollection;
    }

    /**
     * get account by amplifr id
     * @param int $projectId
     * @param int $accountId
     * @return \SplObjectStorage of Accounts
     * @throws AmplifrException
     */
    public function getAccountById($projectId, $accountId)
    {
        $allAccounts = $this->getAccounts($projectId);
        $obResult = new \SplObjectStorage();
        foreach ($allAccounts as $cnt => $itemAccount) {
            /**
             * @var AccountInterface $itemAccount
             */
            if ($itemAccount->getId() === (int)$accountId) {
                $obResult->attach($itemAccount);
            }
        }
        return $obResult;
    }

    /**
     * @param $projectId
     * @return \SplObjectStorage of Accounts
     * @throws AmplifrException
     */
    public function getUsers($projectId)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/users', $projectId), 'GET', array());
        $obCollection = new \SplObjectStorage();
        foreach ($arResult['result']['users'] as $cnt => $arItemAccount) {
            $obCollection->attach(new User($arItemAccount));
        }
        return $obCollection;
    }

    /**
     * @param $projectId
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @return StatReportInterface
     * @throws AmplifrException
     */
    public function getStatReport($projectId, \DateTime $dateFrom, \DateTime $dateTo)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/stats', $projectId), 'GET', array(
            'from' => $dateFrom->format('Y-m-d'),
            'to' => $dateTo->format('Y-m-d')
        ));
        return new StatReport($arResult['result']['stats']);
    }

    /**
     * @param $projectId
     * @param $amplifrPublicationId
     * @return \SplObjectStorage
     * @throws AmplifrException
     */
    public function getStatByPublicationId($projectId, $amplifrPublicationId)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/stats/%d', $projectId, $amplifrPublicationId), 'GET',
            array());

        $obStorage = new \SplObjectStorage();

        if (is_array($arResult['result'])) {
            $arResult['result']['stats']['id'] = $amplifrPublicationId;
            $obStorage->attach(new StatPublication($arResult['result']['stats']));
        }

        if ('not found' === $arResult['result']) {
            $this->log->warning(sprintf('publication with id [%d] for project [%d] not found, stat report not created',
                $amplifrPublicationId, $projectId));
        }

        return $obStorage;
    }

    /**
     * @param $projectId
     * @param $publicationUrl
     * @throws AmplifrException
     * @return \SplObjectStorage
     */
    public function getStatByPublicationUrl($projectId, $publicationUrl)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/stats/by_link', $projectId), 'GET', array(
            'link' => $publicationUrl
        ));

        $obStorage = new \SplObjectStorage();
        if (is_array($arResult['result'])) {
            // in result data structure we can't find publication id, but we have publication URL
            $arResult['result']['stats']['id'] = $this->getPublicationIdFromAmplifrPublicationUrl($arResult['result']['stats']['pubs'][-1]['url']);
            $obStorage->attach(new StatPublication($arResult['result']['stats']));
        }

        if ('not found' === $arResult['result']) {
            $this->log->warning(sprintf('publication with url [%s] for project [%d] not found, stat report not created',
                $publicationUrl, $projectId));
        }

        return $obStorage;
    }

    /**
     * @param $publicationUrl
     * @return int
     */
    private function getPublicationIdFromAmplifrPublicationUrl($publicationUrl)
    {
        return (int)substr($publicationUrl, strrpos($publicationUrl, ':') + 1);
    }

    /**
     * get post list
     *
     * @param $projectId
     * @param int $pageNumber
     * @param int $postsPerPage
     * @param string $order
     *
     * @throws AmplifrException
     *
     * @return PaginationInterface
     * @todo debug
     */
    public function getPostList($projectId, $pageNumber = 1, $postsPerPage = 25, $order = 'DESC')
    {
        $arResult = $this->executeApiRequest(
            sprintf('/projects/%d/posts/?%s', $projectId, http_build_query(array(
                'page' => $pageNumber,
                'per_page' => $postsPerPage,
//                'today' => 'true',
                'order' => $order
            ))), 'GET');
        $obCollection = new \SplObjectStorage();
        foreach ($arResult['result']['posts'] as $cnt => $arPostItem) {
            $obCollection->attach(new Post($arPostItem));
        }

        return new Pagination($obCollection, (int)$arResult['result']['pagination']['current_page'],
            (int)$arResult['result']['pagination']['total_pages']);
    }

    /**
     * get information about post
     *
     * @param $projectId
     * @param $postId
     *
     * @throws AmplifrException
     *
     * @return \SplObjectStorage
     */
    public function getPost($projectId, $postId)
    {
        $arResult = $this->executeApiRequest(
            sprintf('/projects/%d/posts/%d/', $projectId, $postId), 'GET');
        $obResult = new \SplObjectStorage();
        $obResult->attach(new Post($arResult['result']['post']));
        $obResult->rewind();
        return $obResult;
    }

    /**
     * delete post
     *
     * @param int $projectId
     * @param int $postId
     *
     * @throws AmplifrException
     *
     * @return array
     */
    public function deletePost($projectId, $postId)
    {
        $arResult = $this->executeApiRequest(
            sprintf('/projects/%d/posts/%d/', $projectId, $postId), 'DELETE');
        return $arResult['result'];
    }

    /**
     * add new post to Amplifr
     * @param $projectId
     * @param DraftInterface $obNewPost
     * @throws AmplifrException
     * @return \SplObjectStorage
     */
    public function addNewPost($projectId, DraftInterface $obNewPost)
    {
        $arData = $obNewPost->getData();
        $this->log->debug(sprintf('try to add new post'), array($arData));
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/posts', $projectId), 'POST', $arData);
        $obResult = new \SplObjectStorage();
        $obResult->attach(new Post($arResult['result']['post']));
        $obResult->rewind();
        return $obResult;
    }

    /**
     * @param int $projectId
     * @param int $imageId
     * @throws AmplifrException
     * @return AttachmentInterface
     */
    public function getImage($projectId, $imageId)
    {
        $arResult = $this->executeApiRequest(
            sprintf('/projects/%d/images/%d', $projectId, $imageId), 'GET');
        return new Image($imageId, $arResult['result']['url']);
    }

    /**
     * @param int $projectId
     * @param string $videoFileName
     * @return array
     * @throws AmplifrException
     */
    public function getVideoUploadUrl($projectId, $videoFileName)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/videos/get_upload_url?%s',
            $projectId, http_build_query(array(
                'filename' => $videoFileName
            ))), 'GET');

        return $arResult['result'];
    }

    /**
     * @param $projectId int
     * @param $imageFileName string
     * @return array
     * @throws AmplifrException
     */
    public function getImageUploadUrl($projectId, $imageFileName)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/images/get_upload_url?%s',
            $projectId, http_build_query(array(
                'filename' => $imageFileName
            ))), 'GET');

        return $arResult['result'];
    }

    /**
     * @param string $localFilename
     * @param string $uploadUrl
     * @throws IoAmplifrException
     */
    protected function uploadFileToStorage($localFilename, $uploadUrl)
    {
        if (!is_file($localFilename)) {
            $errorMessage = sprintf('resource [%s] is not a file', $localFilename);
            $this->log->error($errorMessage, array(
                'filename' => $localFilename
            ));
            throw new IoAmplifrException($errorMessage);
        }

        if (!is_readable($localFilename)) {
            $errorMessage = sprintf('file [%s] is not readable', $localFilename);
            $this->log->error($errorMessage, array(
                'filename' => $localFilename
            ));
            throw new IoAmplifrException($errorMessage);
        }

        $fileSize = filesize($localFilename);
        if (false === $fileSize) {
            $errorMessage = sprintf('file size calculate error for file [%s] ', $localFilename);
            $this->log->error($errorMessage, array(
                'filename' => $localFilename
            ));
            throw new IoAmplifrException($errorMessage);
        }

        $fileHandler = fopen($localFilename, 'r');
        if (false === $fileHandler) {
            $errorMessage = sprintf('open file error for [%s]', $localFilename);
            $this->log->error($errorMessage, array(
                'filename' => $localFilename
            ));
            throw new IoAmplifrException($errorMessage);
        }

        $this->log->debug('try to upload file to amplifr backend', array(
            'name' => $localFilename,
            'size' => $fileSize
        ));
        // send file to Amplifr backend
        $backendImageStorageResult = $this->curlWrapper(
            array(
                CURLOPT_CONNECTTIMEOUT => 60,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_INFILE => $fileHandler,
                CURLOPT_INFILESIZE => $fileSize,
                CURLOPT_UPLOAD => true,
                CURLOPT_HTTPHEADER => array(
                    'Content-type: ' . mime_content_type($localFilename),
                    'Content-length: ' . $fileSize
                ),
                CURLOPT_URL => $uploadUrl
            )
        );
        fclose($fileHandler);

        $this->handleNetworkErrors();
        if ($backendImageStorageResult !== '') {
            $errorMessage = sprintf('upload error for file [%s]', $localFilename);
            $this->log->error($errorMessage, array(
                'filename' => $localFilename,
                'upload_url' => $uploadUrl,
                'backend_response' => $backendImageStorageResult
            ));
            throw new IoAmplifrException($errorMessage);
        }
    }

    /**
     * upload local video to amplifr backend
     *
     * @param int $projectId
     * @param string $localFilename
     *
     * @throws AmplifrException
     *
     * @return AttachmentInterface
     */
    public function uploadLocalVideo($projectId, $localFilename)
    {
        // get image upload url in amplifr
        $arUploadFileInfo = $this->getVideoUploadUrl($projectId, basename($localFilename));
        $this->log->debug('upload file info from Amplifr', array($arUploadFileInfo));

        // upload file to S3 backend
        $this->uploadFileToStorage($localFilename, $arUploadFileInfo['presignedUrl']);

        return new Video($arUploadFileInfo['id'], $arUploadFileInfo['publicUrl']);
    }

    /**
     * upload local image to amplifr backend
     *
     * @param int $projectId
     * @param string $localFilename
     *
     * @throws AmplifrException
     *
     * @return AttachmentInterface
     */
    public function uploadLocalImage($projectId, $localFilename)
    {
        // get image upload url in amplifr
        $arUploadFileInfo = $this->getImageUploadUrl($projectId, basename($localFilename));
        $this->log->debug('upload file info from Amplifr', array($arUploadFileInfo));

        // upload file to S3 backend
        $this->uploadFileToStorage($localFilename, $arUploadFileInfo['presignedUrl']);

        // confirm success file upload to backend
        $arResult = $this->executeApiRequest(
            sprintf('/projects/%d/images/%d/commit', $projectId, $arUploadFileInfo['id']), 'POST');

        if ($arResult['result']['status'] !== 'uploaded') {
            $errorMessage = sprintf('upload error for file [%s]', $localFilename);
            $this->log->error($errorMessage, array(
                'upload_file_info' => $arUploadFileInfo,
                'backend_response' => $arResult
            ));
            throw new ApiAmplifrException($errorMessage);
        }

        return new Image($arUploadFileInfo['id'], $arUploadFileInfo['publicUrl']);
    }

    /**
     * upload image by url
     *
     * @param int $projectId
     * @param string $imageUrl
     *
     * @throws AmplifrException
     *
     * @return AttachmentInterface
     */
    public function uploadImageByUrl($projectId, $imageUrl)
    {
        $arResult = $this->executeApiRequest(
            sprintf('/projects/%d/images/upload_from_url', $projectId), 'POST', array(
            'url' => $imageUrl
        ));

        if ($arResult['result']['status'] !== 'uploaded') {
            $errorMessage = sprintf('image upload by URL error [%s]', $imageUrl);
            $this->log->error($errorMessage, array(
                'project_id' => $projectId,
                'image_url' => $imageUrl,
                'backend_response' => $arResult
            ));
            throw new ApiAmplifrException($errorMessage);
        }
        return $this->getImage((int)$projectId, (int)$arResult['result']['id']);
    }

    /**
     * Execute a request API to Amplifr using cURL
     *
     * @param string $url
     * @param string $requestType
     * @param array $additionalPostParameters
     *
     * @throws AmplifrException
     *
     * @return array
     *
     */
    protected function executeApiRequest($url, $requestType, array $additionalPostParameters = array())
    {
        if ('' === $this->getAccessToken()) {
            $errorMessage = sprintf('access token is empty, set application token with method setAccessToken');
            $this->log->error($errorMessage, $this->getContext());
            throw new ApiAmplifrException($errorMessage);
        }

        // add auth token to url
        if (parse_url($url, PHP_URL_QUERY) !== null) {
            $url .= '&access_token=' . $this->getAccessToken();
        } else {
            $url .= '?access_token=' . $this->getAccessToken();
        }

        $curlResult = $this->curlWrapper(
            array(
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CUSTOMREQUEST => $requestType,
                CURLOPT_POSTFIELDS => http_build_query($additionalPostParameters),
                CURLOPT_HTTPHEADER => array(
                    'Cache-Control: no-cache',
                    'X-ENVIRONMENT-PHP-VERSION: ' . phpversion()
                ),
                CURLOPT_URL => self::API_ENDPOINT . $url
            )
        );

        $this->handleNetworkErrors();
        $arResult = $this->decodeApiJsonResponse($curlResult);
        $this->handleApiErrors($arResult);
        return $arResult;
    }

    /**
     * @param array $arApiResponse
     * @throws ApiAmplifrException
     */
    protected function handleApiErrors(array $arApiResponse)
    {
        if (!array_key_exists('ok', $arApiResponse)) {
            $errorMsg = sprintf('api error [%s] with code [%d]', $arApiResponse['status'], $arApiResponse['code']);
            $this->log->error($errorMsg, $this->getContext());
            throw new ApiAmplifrException($errorMsg);
        }
    }

    /**
     * @throws IoAmplifrException
     */
    protected function handleNetworkErrors()
    {
        $arRequestInfo = $this->getRequestInfo();
        // handling network level resource errors
        if (!in_array($arRequestInfo['http_code'], range(200, 204), true)) {
            $errorMsg = sprintf('network error, http code: %d', $arRequestInfo['http_code']);
            $this->log->error($errorMsg, $this->getContext());
            throw new IoAmplifrException($errorMsg);
        }
    }

    /**
     * @param $jsonApiResponse
     * @return mixed
     * @throws AmplifrException
     */
    protected function decodeApiJsonResponse($jsonApiResponse)
    {
        // handling server-side API errors: empty response
        if ($jsonApiResponse === '') {
            $errorMsg = sprintf('empty response from server');
            $this->log->error($errorMsg, $this->getContext());
            throw new AmplifrException($errorMsg);
        }

        // handling json_decode errors
        $jsonResult = json_decode($jsonApiResponse, true);
        $jsonErrorCode = json_last_error();
        if (null === $jsonResult && (JSON_ERROR_NONE !== $jsonErrorCode)) {
            /**
             * @todo add function json_last_error_msg()
             */
            $errorMsg = 'fatal error in function json_decode.' . PHP_EOL . 'Error code: ' . $jsonErrorCode . PHP_EOL;
            $this->log->error($errorMsg, $this->getContext());
            throw new AmplifrException($errorMsg);
        }
        return $jsonResult;
    }

    // cURL helper methods
    /**
     * Set custom cURL options, overriding default ones
     * @link http://php.net/manual/en/function.curl-setopt.php
     * @param array $options - array(CURLOPT_XXX => value1, CURLOPT_XXX2 => value2,...)
     */
    public function setCustomCurlOptions(array $options = array())
    {
        $this->customCurlOptions = $options;
    }

    /**
     * @return array
     */
    protected function getCustomCurlOptions()
    {
        return $this->customCurlOptions;
    }

    /**
     * @return array
     */
    protected function getDefaultCurlOptions()
    {
        if (null === $this->defaultCurlOptions) {
            return array();
        }
        return $this->defaultCurlOptions;
    }

    /**
     * @param array $defaultCurlOptions
     */
    protected function setDefaultCurlOptions(array $defaultCurlOptions)
    {
        $this->defaultCurlOptions = $defaultCurlOptions;
    }

    /**
     * cURL wrapper
     * @param $arCurlOptions array
     * @throws IoAmplifrException
     * @return mixed
     */
    protected function curlWrapper(array $arCurlOptions = array())
    {
        // build default cURL options array
        $curlOptions = array_replace($this->getDefaultCurlOptions(), $arCurlOptions);

        $arCustomCurlOptions = $this->getCustomCurlOptions();
        if (is_array($arCustomCurlOptions)) {
            foreach ($arCustomCurlOptions as $customCurlOptionKey => $customCurlOptionValue) {
                $curlOptions[$customCurlOptionKey] = $customCurlOptionValue;
            }
        }

        $this->rawRequest = $curlOptions;
        $this->log->debug(sprintf('try send request'), array(
            'url' => $curlOptions[CURLOPT_URL],
            'curl_options' => $this->getRawRequest()
        ));

        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $curlResult = curl_exec($curl);
        $curlErrorNumber = curl_errno($curl);
        $this->requestInfo = curl_getinfo($curl);
        curl_close($curl);
        $this->rawResponse = $curlResult;

        /**
         * @todo add support CURLOPT_TIMEOUT error types
         * In the array returned from curl_getinfo($ch), compare the connect_time to the the vale you used for the CURLOPT_CONNECTTIMEOUT option.
         * You can also compare total_time to the value you used for CURLOPT_TIMEOUT. If you dump out curl_getinfo($ch) you will see there are a bunch
         * of other timers as well that you can deduce whatever you need.
         */

        if ($curlErrorNumber !== 0) {
            $errorMsg = sprintf('cURL error - %d', $curlErrorNumber);
            $this->log->error($errorMsg, $this->getContext());
            throw new IoAmplifrException($errorMsg);
        }
        return $curlResult;
    }
}