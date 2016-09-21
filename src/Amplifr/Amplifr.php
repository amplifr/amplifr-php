<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr;


use Amplifr\Exceptions\AmplifrException;
use Amplifr\Exceptions\IoAmplifrException;
use Amplifr\Accounts\Account;
use Amplifr\Attachments\Image;
use Amplifr\Attachments\AttachmentInterface;
use Amplifr\Posts\Post;
use Amplifr\Posts\PostInterface;
use Amplifr\Projects\Project;
use Amplifr\Users\User;

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
     * @var string SDK version
     */
    const API_VERSION = '1.0.0';

    /**
     * @var string user agent
     */
    const API_USER_AGENT = 'amplifr-php-sdk';

    /**
     * @var string
     */
    private $applicationToken;

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
     * Amplifr constructor.
     * @param $applicationToken
     * @param $accessToken
     * @param LoggerInterface|null $obLogger
     * @throws  AmplifrException
     */
    public function __construct($applicationToken, $accessToken, LoggerInterface $obLogger = null)
    {
        $this->setApplicationToken($applicationToken);
        $this->setAccessToken($accessToken);
        $this->setDefaultCurlOptions(array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_USERAGENT => strtolower(self::API_USER_AGENT . '-v' . self::API_VERSION),
        ));

        if ($obLogger !== null) {
            $this->log = $obLogger;
        } else {
            $this->log = new NullLogger();
        }
        $this->log->debug('init Amplifr API wrapper',
            array('application_token' => $this->applicationToken, 'access_token' => $this->accessToken));
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
            'application_token' => $this->getApplicationToken(),
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
    protected function getApplicationToken()
    {
        return $this->applicationToken;
    }

    /**
     * @param $applicationToken
     */
    protected function setApplicationToken($applicationToken)
    {
        $this->applicationToken = (string)$applicationToken;
    }

    /**
     * @return string
     */
    protected function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param $accessToken
     */
    protected function setAccessToken($accessToken)
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
     * @return StatPublication | null
     * @throws AmplifrException
     */
    public function getStatByPublicationId($projectId, $amplifrPublicationId)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/stats/%d', $projectId, $amplifrPublicationId), 'GET',
            array());
        $arResult['result']['stats']['id'] = $amplifrPublicationId;
        return new StatPublication($arResult['result']['stats']);
    }

    /**
     * @param $projectId
     * @param $publicationUrl
     * @throws AmplifrException
     * @return StatPublication | null
     */
    public function getStatByPublicationUrl($projectId, $publicationUrl)
    {
        $arResult = $this->executeApiRequest(sprintf('/projects/%d/stats/by_link', $projectId), 'GET', array(
            'link' => $publicationUrl
        ));
        $arPublicationId = array_keys($arResult['result']['stats']['pubs']);
        $arResult['result']['stats']['pubs'][$arPublicationId[0]]['id'] = $arPublicationId[0];
        return new StatPublication($arResult['result']['stats']);
    }

    /**
     * @param $projectId
     * @param int $pageNumber
     * @param int $postsPerPage
     * @param string $order
     * @throws AmplifrException
     * @return Result
     * @todo debug
     */
    public function getPostList($projectId, $pageNumber = 1, $postsPerPage = 25, $order = 'DESC')
    {
        $arResult = $this->executeApiRequest(
            sprintf('/projects/%d/posts/?%s', $projectId, http_build_query(array(
                'page' => $pageNumber,
                'per_page' => $postsPerPage,
//                'today' => false,
//                'order' => $order
            ))), 'GET');

        $obCollection = new \SplObjectStorage();
        foreach ($arResult['result']['posts'] as $cnt => $arPostItem) {
            $obCollection->attach(new Post($arPostItem));
        }
        return new Result($obCollection, $arResult['result']['pagination']['current_page'],
            $arResult['result']['pagination']['total_pages']);
    }

    /**
     * @param $projectId
     * @param $postId
     * @throws AmplifrException
     * @return PostInterface
     *
     * @todo debug
     */
    public function getPost($projectId, $postId)
    {
        $arResult = $this->executeApiRequest(
            sprintf('/projects/%d/posts/%d/', $projectId, $postId), 'GET');
        return new Post($arResult['result']);
    }

    /**
     * @param $projectId
     * @param $postId
     * @todo fix
     */
    public function deletePost($projectId, $postId)
    {
    }

    /**
     * @param $arNewPost
     * @todo fix
     */
    public function addNewPost($arNewPost)
    {
    }

    /**
     * @param $projectId
     * @param $imageId
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
     * @param $projectId
     * @param $imageFilename
     * @throws AmplifrException
     */
    public function uploadImage($projectId, $imageFilename)
    {
        if (!file_exists($imageFilename)) {
            $errorMessage = sprintf('file [%s] not found', $imageFilename);
            $this->log->error($errorMessage, array(
                'project_id' => $projectId,
                'image_filename' => $imageFilename
            ));
            throw new AmplifrException($errorMessage);
        }

        // get image upload url in amplifr
        $arUploadFileInfo = $this->getImageUploadUrl($projectId, basename($imageFilename));
        $this->log->debug('get upload file info from Amplifr', array($arUploadFileInfo));

        // send file to Amplifr backend
        $curlResult = $this->curlWrapper(
            array(
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => array(
                    'file' => $this->getCurlFileValue($imageFilename, mime_content_type($imageFilename),
                        basename($imageFilename))
                ),
                CURLOPT_URL => $arUploadFileInfo['presignedUrl']
            )
        );
//        var_dump($curlResult);
//        $this->sendFile('https://mesilov.b24.io/dev/amplifr/test.php', $imageFilename);

        //
//        var_dump($arUploadFileInfo);

        //

        // commit image id
        // return Image object
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
        $curlResult = $this->curlWrapper(
            array(
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CUSTOMREQUEST => $requestType,
                CURLOPT_POSTFIELDS => http_build_query($additionalPostParameters),
                CURLOPT_HTTPHEADER => array(
                    'Cache-Control: no-cache',
                    'X-ACCESS-TOKEN: ' . $this->accessToken,
                    'X-APP-TOKEN: ' . $this->applicationToken,
                    'X-ENVIRONMENT-PHP-VERSION: ' . phpversion()
                ),
                CURLOPT_URL => self::API_ENDPOINT . $url
            )
        );

        // handling server-side API errors: empty response
        if ($curlResult === '') {
            $errorMsg = sprintf('empty response');
            $this->log->error($errorMsg, $this->getContext());
            throw new AmplifrException($errorMsg);
        }

        // handling json_decode errors
        $jsonResult = json_decode($curlResult, true);
        unset($curlResult);
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
     * helper function for create \CURLFile object
     * @see https://github.com/guzzle/guzzle/blob/3a0787217e6c0246b457e637ddd33332efea1d2a/src/Guzzle/Http/Message/PostFile.php#L90
     * @param $filename string
     * @param $contentType string
     * @param $postFilename string
     * @return \CURLFile|string
     */
    protected function getCurlFileValue($filename, $contentType, $postFilename)
    {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $contentType, $postFilename);
        }

        // Use the old style if using an older version of PHP
        $value = "@{$filename};filename=" . $postFilename;
        if ($contentType) {
            $value .= ';type=' . $contentType;
        }

        return $value;
    }

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

        // handling network level resource errors
        if (!in_array($this->requestInfo['http_code'], range(200, 204), true)) {
            $errorMsg = sprintf('http code: %d', $this->requestInfo['http_code']);
            $this->log->error($errorMsg, $this->getContext());
            throw new IoAmplifrException($errorMsg);
        }

        return $curlResult;
    }
}