<?php

declare(strict_types=1);

namespace Kosmosafive\ProductionCalendarBx\ValueObject;

use Bitrix\Main\Type\Date as BitrixDate;
use Kosmosafive\ProductionCalendar\ValueObject\Day\Type;

readonly class CalendarDay
{
    public function __construct(
        public BitrixDate $date,
        public Type $type,
        public bool $isStandardWorkday = true,
        public string $name = '',
        public bool $isStandardWeekend = false,
        public ?BitrixDate $transferredFrom = null
    ) {
    }
}
