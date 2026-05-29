<?php

declare(strict_types=1);

namespace Kosmosafive\ProductionCalendarBx\ValueObject;

use Bitrix\Main\Type\Date as BitrixDate;
use Kosmosafive\ProductionCalendar\ValueObject\Day\Type;

readonly class Day
{
    public function __construct(
        public BitrixDate $date,
        public Type $type,
        public string $name = '',
        public ?BitrixDate $transferredFrom = null
    ) {
    }
}
