# Производственный календарь

Интеграция с Bitrix решения [kosmosafive/production-calendar](https://github.com/kosmosafive/production-calendar).

## Установка

В composer.json (пример для директории local) проекта добавьте

```json
{
  "require": {
    "wikimedia/composer-merge-plugin": "dev-master",
    "composer/installers": "^2.3"
  },
  "config": {
    "allow-plugins": {
      "composer/installers": true,
      "wikimedia/composer-merge-plugin": true
    }
  },
  "extra": {
    "merge-plugin": {
      "include": [
        "../bitrix/composer-bx.json",
        "modules/*/composer.json"
      ],
      "recurse": true,
      "replace": true,
      "ignore-duplicates": false,
      "merge-dev": true,
      "merge-extra": false,
      "merge-extra-deep": false,
      "merge-scripts": false,
      "merge-ignore-none": true
    },
    "installer-paths": {
      "modules/{$name}/": [
        "type:bitrix-d7-module"
      ]
    }
  }
}
```

Установите зависимости. Добавьте модуль

```bash
composer require kosmosafive/kosmosafive.productioncalendar
```

Подключите автозагрузку из vendor. Например, в файле /bitrix/.settings.php или /bitrix/.settings_extra.php добавьте

```php
$vendorAutoload = dirname(__DIR__) . '/local/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}
```

## Конфигурация модуля

Конфигурация указывается в файле /bitrix/.settings.php или /bitrix/.settings_extra.php.

*folder* — путь до директории с файлами модуля (по умолчанию /upload/production_calendar/)
*http_client* — набор опций HttpClient (по умолчанию используется базовая конфигурация).

```php
return [
    'kosmosafive.commandline' => [
        'value' => [
            'folder' => $_SERVER['DOCUMENT_ROOT'] . '/upload/production_calendar/',
            'http_client' => null,        
        ],
    ],
];
```

Собственный календарь в формате страна_год.json (например, by_2023.json) можно разместить в директории folder/custom.
