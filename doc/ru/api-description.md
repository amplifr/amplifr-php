# API v1

## Общая информация

`url: https://amplifr.com/api/v1`

Для каждого запроса необходим access_token параметром `access_token` или заголовком `X-ACCESS-TOKEN`, а так же app_token параметром `app_token` или заголовком `X-APP-TOKEN`.

`access_token` - токен конкретного пользователя Amplifr
`app_token` - токен API приложения, зарегистрированного в Amplifr (на данный момент выдается по запросу)

## Endpoints

### Base

#### `POST` `/authorize_token` – авторизация пользователя

> Принимает параметр `data` для регистрации обратной подписки пользователя. На данный момент закрытая функция

* `http status 200, { "ok":"true", "result":{"status":"authorized", "user":{USER_OBJECT}} }` – токен валиден, выдана информация по пользователю
* `http status 401, { "ok":"true", "result":{"status":"unathorized","code":"401"}` – токена не существует или он неактивен

> `POST` `/update_subscription` – обновление подписки пользователя (закрытый метод)
>
> Принимает параметр `data` для регистрации обратной подписки пользователя. На данный момент закрытая функция

*Во всех удачных ответах присутствует два аттрибута - "ok":true и "result": содержащий ответ от сервера. В дальнейшем описании этот уровень опущен, показано только содержимое "result"*


### Projects

#### `GET` `/projects` – список всех проектов, доступных пользователю

`RESPONSE:`
```
{"projects":[
  {
    "id":1234,
    "name":"Amplifr Blog",
    "social_accounts":[
      {
        "id":1234,
        "name":"amplifr",
        "url":"https://facebook.com/12341234",
        "avatar":"https://facebook.com/avatar.png",
        "network":"facebook",
        "networkAbbr":"fb",
        "active":true,
        "publishable":true
      },
      ...
    ],
    "users":[
      {
        "id":1234,
        "role":"admin",
        "name":"Alexey Gaziev",
        "email":"email@example.com",
        "confirmed":true,
        "timezone":"Europe/Moscow",
        "tzUtcOffset":3
      },
      ...
    ]
    "bestTime": [ массив лучшего времени три слота c часами по дням недели ]
  }
]}
```


### Accounts

#### `GET` `/projects/:project_id/accounts` – информация об аккаунтах, аналогичная информации в проектах

`RESPONSE:`
```
{"accounts":[
  {
    "id":1234,
    "name":"amplifr",
    "url":"https://facebook.com/12341234",
    "avatar":"https://facebook.com/avatar.png",
    "network":"facebook",
    "networkAbbr":"fb",
    "active":true,
    "publishable":true
  },
  ...
]}
```


### Users

#### `GET */projects/:project_id/users*` – информация о пользователях, аналогичная информации в проектах

`*response*:`
```
{"users":[
  {
    "id":1234,
    "role":"admin",
    "name":"Alexey Gaziev",
    "email":"email@example.com",
    "confirmed":true,
    "timezone":"Europe/Moscow",
    "tzUtcOffset":3
  },
  ...
]}
```

### Posts

#### `GET` `/projects/:project_id/posts` – список постов со всей информацией - статусы публикаций, аттачменты и тп

Параметры:

* `page` - номер страницы с постами
* `per_page` - число постов на странице, максимум `100`
* `today` - `true/false` - выдает только сегодняшние посты начиная с самого раннего
* `order` - `ASC/DESC` - порядок выдачи постов, по умолчанию - `publish_at DESC` - то есть последние к публикации вперед

Обратите внимание на ключ `pagination` в выдаче для `posts` - там есть `current_page` и `total_pages`


`RESPONSE:`
```
{"posts":[
  {
    "id":552225,
    "url":"https://producthunt.com/tech/amplifr",
    "time":1461778834,
    "text":"Hey everyone, thank you so much for all the support on @producthunt! Join the hunt!  https://producthunt.com/tech/amplifr ",
    "author":20,
    "clicks":28,
    "likes":3,
    "shares":1,
    "comments":0,
    "clickCounting":true,
    "errors":[],
    "states":{"4415":"published"},
    "socials":[4415],
    "subforms":[
      {
        "socials":[4417],
        "text":"Hey everyone, thank you so much for all the support on Product Hunt! 300 and counting! https://producthunt.com/tech/amplifr ",
        "url":"https://producthunt.com/tech/amplifr",
        "previews":["http://imgcdn.amplifr.dev/development/images/social_images/000/650/484/img.jpg?s=210127a3491324d86cda3ff872f5ae9ea3cf9728"],
        "attachments":["image:650484"]
      }
    ],
    "publications":{
      "4415":"https://twitter.com/amplifr/status/725379133518270464",
      "4417":"https://facebook.com/119691744774787_974911525919467"},
    "attachments":["image:650481"],
    "previews":["https://amplifr-direct.s3-eu-west-1.amazonaws.com/social_images/image/6c7743b2-23a4-496a-ab06-4748ba7fb831.jpg"]
  },
  ...
],"pagination":{"current_page":1,"total_pages":18}}
```


#### `GET` `/projects/:project_id/posts/:post_id` – информация об одном посте

`RESPONSE:`
```
{"post":{
  "id":...
}}
```

#### `POST` `/projects/:project_id/posts` – публикация поста

Параметры:
* `text` - сообщение
* `url` - ссылка, из которой развернуть карточку
* `time` - время публикации (`"2016-05-16T13:50:00.000Z"`) может быть пустым - опубликовать сейчас
* `attachments` - массив аттачментов в формате `["image:1", "image:2", "video:2"]`, `"attachment_type":"id_in_amplifr"`
* `socials` - массив айдишников аккаунтов куда публиковать
* `subforms` - массив объектов дополнительных форм - отдельные публикации в отдельные соцсети. Объект состоит из вышеперечисленых параметров за исключением времени

`RESPONSE:`
```
{"post":{
  "id":...
  }
}
```


#### `DELETE` `/projects/:project_id/posts/:post_id` – инициация удаления поста и удаления публикаций из соцсетей (в которых это разрешено)

`RESPONSE:`
```
{"post_id":1234,"status":"destroyed"}
```

### Images

#### `GET` `/projects/:project_id/images/:image_id` – возвращает ссылку на картинку

`RESPONSE:`
```
{"url":"http://image.com/url.jpg"}
```

#### `GET` `/projects/:project_id/images/get_upload_url` – возвращает ссылку для загрузки картинки (`presigned_url`), айди картинки в амплифре и ссылку на картинку, где она будет доступна после загрузки

Параметры:
* `filename` – имя файла к загрузке

`RESPONSE:`
```
{
  "id":15,
  "publicUrl":"https://amplifr-direct.s3-eu-west-1.amazonaws.com/social_images/image/7ce18d0d-bfb0-4b6b-843e-7f34841a7e3d.jpg",
  "presignedUrl":"https://amplifr-direct.s3-eu-west-1.amazonaws.com/social_images/image/7ce18d0d-bfb0-4b6b-843e-7f34841a7e3d.jpg?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJBU6GIQT2VZIPRDA%2F20160516%2Feu-west-1%2Fs3%2Faws4_request&X-Amz-Date=20160516T133556Z&X-Amz-Expires=900&X-Amz-SignedHeaders=host&x-amz-acl=public-read&X-Amz-Signature=af9ddc9206909f8be1d0f12e1b1cc3cac9da1eca5ccb0d97c341fc55a90a226d"
}
```

#### `POST` `/projects/:project_id/images/:image_id/commit` – подтверждает что картинка загружена на s3 в бд амплифра, картинку можно использовать для публикаций

`RESPONSE:`
```
{"status":"uploaded","id":1234}
```

#### `POST` `/projects/:project_id/images/upload_from_url` – загрузка изображения по ссылке

Параметры:
* `url` - ссылка на картинку

`RESPONSE:`
```
{"status":"uploaded","id":1234}
```

### Videos

#### `GET` `/projects/:project_id/videos/:video_id` – возвращает ссылку на видео

`RESPONSE:`
```
{"url":"http://video.com/url.mp4"}
```

#### `GET` `/projects/:project_id/videos/get_upload_url` – возвращает ссылку для загрузки видео (presigned_url), айди видео в амплифре и ссылку на видео, где оно будет доступно после загрузки

Параметры:
* `filename` – имя файла к загрузке

`RESPONSE:`
```
{
  "id":13,
  "publicUrl":"https://amplifr-direct.s3-eu-west-1.amazonaws.com/social_videos/video/72867ba7-2cca-4180-8f83-19f4a04db7c3.mp4",
  "presignedUrl":"https://amplifr-direct.s3-eu-west-1.amazonaws.com/social_videos/video/72867ba7-2cca-4180-8f83-19f4a04db7c3.mp4?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJBU6GIQT2VZIPRDA%2F20160516%2Feu-west-1%2Fs3%2Faws4_request&X-Amz-Date=20160516T134533Z&X-Amz-Expires=900&X-Amz-SignedHeaders=host&x-amz-acl=public-read&X-Amz-Signature=e8c97b4dfe9ce74ccd86a8b8e2c258390801883dbf08df179e965651f3306e27"
}
```

### Stats

#### `GET` `/projects/:project_id/stats` – возвращает общую статистику по аккаунтам + три лучшие публикации за период

Параметры:
* `from` – дата в формате YYYY-MM-DD
* `to` – дата в формате YYYY-MM-DD

`RESPONSE:`
```
{"stats":
  {
    "from": "2016-06-01",
    "to": "2016-06-07",
    "networks": {
      "6168": {
        "network": "fb",
        "name": "Amplifr",
        "url": "https://www.facebook.com/app_scoped_user_id/1322232597/",
        "subscribers": 1759,
        "stats": {
          "likes":123,
          "shares":123,
          "comments":123,
          "linkClicks":123,
          "videoPlays":123,
          "uniqueViews":123,
          "fanUniqueViews":123,
          "totalViews":123
        },
        "subscribersDiff":"-1",
      },
    }
  }
  "bestPubs": {
    "1": {
      "network": "ig",
      "networkName": "@amplifr",
      "url": "https://www.instagram.com/p/12341234/",
      "preface": "Lalala",
      "stats": {
        "likes":123
        "shares":123,
        "comments":123,
        "linkClicks":123,
        "videoPlays":123,
        "uniqueViews":123,
        "fanUniqueViews":123,
        "totalViews":123
      }
    },
    "2": {
    ...
    },
    "3": {
    ...
    }
  }
}
```

#### `GET` `/projects/:project_id/stats/:id` – возвращает статистику по публикацям поста по id поста в Амплифере

Параметры:
* `:id` – id поста в Амплифере

`RESPONSE:`
```
{"stats":{
  "pubs": {
    "5854973": {
      "network": "fb",
      "name": "Amplifr",
      "url": "https://www.facebook.com/12341234",
      "stats": {
        "likes": 43
        ...
      }
    },
    "585497666666": {
      "network": "vk",
      ...
    }
    "-1": {
      "network": "total",
      "name": "Open in Amplifr",
      "url": "https://amplifr.com/p/1234/plan#post:1234:999999",
      "stats": {
        "likes": 0,
        "shares": 0,
        "comments": 0,
        "linkClicks": 0,
        "videoPlays": 0,
        "uniqueViews": 0,
        "fanUniqueViews": 0,
        "totalViews": 0
      }
    }
  },
  "preface": "Lalala"
}
```

#### `GET` `/projects/:project_id/stats/by_link` – возвращает статистику по публикацям (по публикации, если она была сделана не через Амплифер) по ссылке на нее в соцсети, если Амплифер может ее найти

Параметры:
* `link` – ссылка на публикацию

`RESPONSE:`
```
{"stats":{
  "pubs": {
    "5854973": {
      "network": "fb",
      "name": "Amplifr",
      "url": "https://www.facebook.com/12341234",
      "stats": {
        "likes": 43
        ...
      }
    },
    "585497666666": {
      "network": "vk",
      ...
    }
    "-1": {
      "network": "total",
      "name": "Open in Amplifr",
      "url": "https://amplifr.com/p/1234/plan#post:1234:999999",
      "stats": {
        "likes": 0,
        "shares": 0,
        "comments": 0,
        "linkClicks": 0,
        "videoPlays": 0,
        "uniqueViews": 0,
        "fanUniqueViews": 0,
        "totalViews": 0
      }
    }
  },
  "preface": "Lalala"
}
```