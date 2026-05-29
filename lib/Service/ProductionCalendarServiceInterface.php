<?php

declare(strict_types=1);

namespace Kosmosafive\ProductionCalendarBx\Service;

use Kosmosafive\ProductionCalendarBx\BitrixProductionCalendar;

interface ProductionCalendarServiceInterface
{
    public function create(string $country): BitrixProductionCalendar;
}