<?php

declare(strict_types=1);

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Kosmosafive\ProductionCalendar\Http\Message\RequestFactory;
use Kosmosafive\ProductionCalendar\Service;
use Bitrix\Main\Web\HttpClient;
use Kosmosafive\ProductionCalendar\SimpleCache\Cache;
use Kosmosafive\ProductionCalendar\Provider;

return [
    'services' => [
        'value' => [
            Service\ProductionCalendarServiceInterface::class => [
                'constructor' => static function () {
                    $moduleId = 'kosmosafive.productioncalendar';
                    $moduleConfiguration = (array)Configuration::getInstance()->get($moduleId);

                    $httpClient = new HttpClient($moduleConfiguration['http_client'] ?? null);
                    $requestFactory = new RequestFactory();
                    $folder = $moduleConfiguration['folder'] ?? Application::getDocumentRoot(
                    ) . '/upload/production_calendar/';
                    $cache = new Cache($folder . 'cache/');

                    $apiProvider = new Provider\XmlCalendarProvider($httpClient, $requestFactory);

                    $composite = new Provider\CompositeProvider(
                        new Provider\JsonProvider($folder . 'custom'),
                        new Provider\CachedProvider($apiProvider, $cache)
                    );

                    return new Service\ProductionCalendarService(
                        $composite
                    );
                }
            ],
        ],
        'readonly' => true,
    ],
];
