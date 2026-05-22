<?php

declare(strict_types=1);

namespace Kosmosafive\ProductionCalendar\Service;

use Kosmosafive\ProductionCalendar\ProductionCalendar;
use Kosmosafive\ProductionCalendar\Provider\HolidayProviderInterface;

readonly class ProductionCalendarService implements ProductionCalendarServiceInterface
{
    public function __construct(
        protected HolidayProviderInterface $provider
    ) {
    }

    public function create(string $country): ProductionCalendar
    {
        return new ProductionCalendar($this->provider, $country);
    }
}