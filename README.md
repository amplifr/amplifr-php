# amplifr-php

## Installation
Install the latest version with

```bash
$ composer require amplifr/amplifr-php
```
## Documentation
- [API documentation](http://docs.amplifr.apiary.io/#)

## About

### Requirements
- php: >=5.3.2
- ext-json: *
- ext-curl: *
- Monolog: optional 

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/amplifr/amplifr-php/issues)

### License
amplifr-php is licensed under the MIT License - see the `MIT-LICENSE.txt` file for details

## Examples

### Basic Usage

```php
<?php

?>
```

### Publish new post to Amplifr to one social network account
```php
<?php
// prepare draft post 
$accountId = 12345;
$imgFilename = __DIR__ . '/img.jpg';

/**
 * @var $obAttach \Amplifr\Attachments\AttachmentInterface
 */
$obAttach = $obAmplifr->uploadLocalImage($projectId, $imgFilename);
/**
 * @var $obDraft \Amplifr\Posts\DraftInterface
 */
$obDraft = new \Amplifr\Posts\Draft('Hello world! #' . mt_rand(), 'https://amplifr.com', new DateTime());
$obDraft->addAttachment($obAttach);
$obDraft->setSocialNetworkAccounts($obAmplifr->getAccountById($projectId, $accountId));
/**
 *@var $postStorage \SplObjectStorage
 */
$postStorage = $obAmplifr->addNewPost($projectId, $obDraft);
/**
 * @var \Amplifr\Posts\PostInterface
 */
$postItem = $postStorage->current();
print(sprintf('id: %s' . PHP_EOL, $postItem->getId()));
print(sprintf('url: %s' . PHP_EOL, print_r($postItem->getUrl(), true)));
print(sprintf('post time: %s' . PHP_EOL, $postItem->getTime()->format(DATE_ISO8601)));
print(sprintf('text: %s' . PHP_EOL, $postItem->getText()));
print(sprintf('author_id: %s' . PHP_EOL, $postItem->getAuthorId()));
print(sprintf('errors: %s' . PHP_EOL, print_r($postItem->getErrors(), true)));
print(sprintf('states: %s' . PHP_EOL, print_r($postItem->getStates(), true)));
print(sprintf('social networks: %s' . PHP_EOL, print_r($postItem->getSocialNetworks(), true)));
print(sprintf('publications: %s' . PHP_EOL, print_r($postItem->getPublications(), true)));
print(sprintf('attachments: %s' . PHP_EOL, print_r($postItem->getAttachmentsId(), true)));
print(sprintf('previews: %s' . PHP_EOL, print_r($postItem->getPreviews(), true)));
print(sprintf('counters: %s' . PHP_EOL, print_r($postItem->getStatistics(), true)));
print(sprintf('is click counting: %s' . PHP_EOL, print_r($postItem->isClickCounting(), true)));
print(sprintf('subforms: %s' . PHP_EOL, print_r($postItem->getSubforms(), true)));
?>
``` 
### Work with Accounts
```php
<?php
/**
* @var $socialAccountsStorage \SplObjectStorage
 */
$socialAccountsStorage = $obAmplifr->getAccounts($projectId);
foreach ($socialAccountsStorage as $itemAccount) {
    printf('-------- accounts in social networks:' . PHP_EOL);
    /**
     * @var $itemAccount \Amplifr\Accounts\AccountInterface
     */
    printf('id: %d' . PHP_EOL, $itemAccount->getId());
    printf('name: %s' . PHP_EOL, $itemAccount->getName());
    printf('network: %s' . PHP_EOL, $itemAccount->getNetwork());
    printf('url: %s' . PHP_EOL, $itemAccount->getUrl());
    printf('avatar: %s' . PHP_EOL, $itemAccount->getAvatar());
    printf('network abbreviation: %s' . PHP_EOL, $itemAccount->getNetworkAbbreviation());
    printf('is active: %s' . PHP_EOL, $itemAccount->isActive());
    printf('is publishable: %s' . PHP_EOL, $itemAccount->isPublishable());
}
?>
```
### Work with Projects
```php
<?php
$projects = $obAmplifr->getProjects();
foreach ($projects as $itemProject) {
    printf('---- project' . PHP_EOL);
    /**
     * @var $itemProject \Amplifr\Projects\ProjectInterface
     */
    printf('id: %s' . PHP_EOL, $itemProject->getId());
    printf('name: %s' . PHP_EOL, $itemProject->getName());

    $socialAccounts = $itemProject->getSocialAccounts();
    foreach ($socialAccounts as $itemAccount) {
        printf('-------- social account' . PHP_EOL);
        /**
         * @var $itemAccount \Amplifr\Accounts\AccountInterface
         */
        printf('id: %d' . PHP_EOL, $itemAccount->getId());
        printf('name: %s' . PHP_EOL, $itemAccount->getName());
        printf('network: %s' . PHP_EOL, $itemAccount->getNetwork());
        printf('url: %s' . PHP_EOL, $itemAccount->getUrl());
        printf('avatar: %s' . PHP_EOL, $itemAccount->getAvatar());
        printf('network abbreviation: %s' . PHP_EOL, $itemAccount->getNetworkAbbreviation());
        printf('is active: %s' . PHP_EOL, $itemAccount->isActive());
        printf('is publishable: %s' . PHP_EOL, $itemAccount->isPublishable());
    }

    $users = $itemProject->getUsers();
    foreach ($users as $itemUser) {
        printf('-------- amplifr user' . PHP_EOL);

        /**
         * @var $itemUser \Amplifr\Users\UserInterface
         */
        printf('id: %s' . PHP_EOL, $itemUser->getId());
        printf('name : %s' . PHP_EOL, $itemUser->getName());
        printf('email: %s' . PHP_EOL, $itemUser->getEmail());
        printf('is confirmed: %s' . PHP_EOL, $itemUser->isConfirmed());
        printf('timezone: %s' . PHP_EOL, $itemUser->getTimeZone()->getName());
        printf('time zone utc offset: %s' . PHP_EOL, $itemUser->getTimeZoneUtcOffset());
        printf('role: %s' . PHP_EOL, $itemUser->getRole());
    }
}
?>
```

### Work with statistics report, filter by date range
```php
<?php
printf('work with statistics report'.PHP_EOL);
$obStatReport = $obAmplifr->getStatReport($projectId, new \DateTime('01.01.2016'), new \DateTime());

printf('3 best publications: %s' . PHP_EOL, print_r($obStatReport->getBestPublications(), true));
printf('interactions: %s' . PHP_EOL, print_r($obStatReport->getInteractions(), true));
$obNetworks = $obStatReport->getNetworks();
foreach ($obNetworks as $itemNetwork) {
    printf('---- social network' . PHP_EOL);
    /**
     * @var $itemNetwork \Amplifr\Networks\NetworkInterface
     */
    printf('id: %s' . PHP_EOL, $itemNetwork->getId());
    printf('code: %s' . PHP_EOL, $itemNetwork->getCode());
    printf('username: %s' . PHP_EOL, $itemNetwork->getUserName());
    printf('url: %s' . PHP_EOL, $itemNetwork->getUrl());
    printf('subscribers count: %s' . PHP_EOL, $itemNetwork->getSubscribersCount());
    printf('subscribers diff count: %s' . PHP_EOL, $itemNetwork->getSubscribersDiffCount());

    /**
     * @var $obStat \Amplifr\Stat\CounterInterface
     */
    $obStat = $itemNetwork->getStatistics();
    printf('network account stat: '.PHP_EOL);
    printf('  likes: %d'.PHP_EOL, $obStat->getLikes());
    printf('  shares: %d'.PHP_EOL, $obStat->getShares());
    printf('  comments: %d'.PHP_EOL, $obStat->getComments());
    printf('  link clicks: %d'.PHP_EOL, $obStat->getLinkClicks());
    printf('  video plays: %d'.PHP_EOL, $obStat->getVideoPlays());
    printf('  unique views: %d'.PHP_EOL, $obStat->getUniqueViews());
    printf('  total views: %d'.PHP_EOL, $obStat->getTotalViews());
}
?>
```
### Work with statistics report, get publication by id

```php
<?php
$publicationId = 12345;
foreach ($obAmplifr->getStatByPublicationId($projectId, $publicationId) as $obStatPublication) {
    /**
     * @var $obStatPublication \Amplifr\Stat\StatPublicationInterface
     */
    printf('publication: ' . PHP_EOL);
    printf('id: %s' . PHP_EOL, $obStatPublication->getPublicationId());
    printf('url: %s' . PHP_EOL, $obStatPublication->getUrl());
    printf('preface: %s' . PHP_EOL, $obStatPublication->getPreface());
    printf('stat: ' . PHP_EOL);
    printf('  likes: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getLikes());
    printf('  shares: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getShares());
    printf('  comments: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getComments());
    printf('  link clicks: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getLinkClicks());
    printf('  video plays: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getVideoPlays());
    printf('  unique views: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getUniqueViews());
    printf('  total views: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getTotalViews());

    foreach ($obStatPublication->getStatPosts() as $obPostItem) {
        /**
         * @var $obPostItem \Amplifr\Stat\StatPostInterface
         */
        printf('  --post in social network: ' . PHP_EOL);
        printf('    id: %s' . PHP_EOL, $obPostItem->getId());
        printf('    url: %s' . PHP_EOL, $obPostItem->getUrl());
        printf('    network: %s' . PHP_EOL, $obPostItem->getNetworkCode());
        printf('    username: %s' . PHP_EOL, $obPostItem->getUserName());
        printf('    stat: ' . PHP_EOL);
        printf('        likes: %d' . PHP_EOL, $obPostItem->getStatistics()->getLikes());
        printf('        shares: %d' . PHP_EOL, $obPostItem->getStatistics()->getShares());
        printf('        comments: %d' . PHP_EOL, $obPostItem->getStatistics()->getComments());
        printf('        link clicks: %d' . PHP_EOL, $obPostItem->getStatistics()->getLinkClicks());
        printf('        video plays: %d' . PHP_EOL, $obPostItem->getStatistics()->getVideoPlays());
        printf('        unique views: %d' . PHP_EOL, $obPostItem->getStatistics()->getUniqueViews());
        printf('        total views: %d' . PHP_EOL, $obPostItem->getStatistics()->getTotalViews());
    }
}
?>
```

### Work with statistics report, get publication by url
```php
<?php
$publicationUrl = '';
foreach ($obAmplifr->getStatByPublicationUrl($projectId, $publicationUrl) as $obStatPublication) {
    /**
     * @var $obStatPublication \Amplifr\Stat\StatPublicationInterface
     */
    printf('publication: ' . PHP_EOL);
    printf('id: %s' . PHP_EOL, $obStatPublication->getPublicationId());
    printf('url: %s' . PHP_EOL, $obStatPublication->getUrl());
    printf('preface: %s' . PHP_EOL, $obStatPublication->getPreface());
    printf('stat: ' . PHP_EOL);
    printf('  likes: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getLikes());
    printf('  shares: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getShares());
    printf('  comments: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getComments());
    printf('  link clicks: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getLinkClicks());
    printf('  video plays: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getVideoPlays());
    printf('  unique views: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getUniqueViews());
    printf('  total views: %d' . PHP_EOL, $obStatPublication->getTotalStatistics()->getTotalViews());

    foreach ($obStatPublication->getStatPosts() as $obPostItem) {
        /**
         * @var $obPostItem \Amplifr\Stat\StatPostInterface
         */
        printf('  --post in social network: ' . PHP_EOL);
        printf('    id: %s' . PHP_EOL, $obPostItem->getId());
        printf('    url: %s' . PHP_EOL, $obPostItem->getUrl());
        printf('    network: %s' . PHP_EOL, $obPostItem->getNetworkCode());
        printf('    username: %s' . PHP_EOL, $obPostItem->getUserName());
        printf('    stat: ' . PHP_EOL);
        printf('        likes: %d' . PHP_EOL, $obPostItem->getStatistics()->getLikes());
        printf('        shares: %d' . PHP_EOL, $obPostItem->getStatistics()->getShares());
        printf('        comments: %d' . PHP_EOL, $obPostItem->getStatistics()->getComments());
        printf('        link clicks: %d' . PHP_EOL, $obPostItem->getStatistics()->getLinkClicks());
        printf('        video plays: %d' . PHP_EOL, $obPostItem->getStatistics()->getVideoPlays());
        printf('        unique views: %d' . PHP_EOL, $obPostItem->getStatistics()->getUniqueViews());
        printf('        total views: %d' . PHP_EOL, $obPostItem->getStatistics()->getTotalViews());
    }
}
?>
```