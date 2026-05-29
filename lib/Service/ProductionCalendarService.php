<?php

declare(strict_types=1);

namespace Kosmosafive\ProductionCalendarBx\Service;

use Kosmosafive\ProductionCalendar\ProductionCalendar;
use Kosmosafive\ProductionCalendar\Provider\ProviderInterface;
use Kosmosafive\ProductionCalendarBx\BitrixProductionCalendar;

readonly class ProductionCalendarService implements ProductionCalendarServiceInterface
{
    public function __construct(
        protected ProviderInterface $provider
    ) {
    }

    public function create(string $country): BitrixProductionCalendar
    {
        return new BitrixProductionCalendar(
            new ProductionCalendar($this->provider, $country)
        );
    }
}
