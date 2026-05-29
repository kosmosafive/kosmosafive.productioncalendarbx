<?php

declare(strict_types=1);

namespace Kosmosafive\ProductionCalendarBx;

use Bitrix\Main\ObjectException;
use Bitrix\Main\Type\Date as BitrixDate;
use DateInvalidTimeZoneException;
use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use Kosmosafive\ProductionCalendar\ProductionCalendarInterface;
use Kosmosafive\ProductionCalendarBx\ValueObject\CalendarDay;

class BitrixProductionCalendar
{
    public function __construct(
        private readonly ProductionCalendarInterface $calendar
    ) {
    }

    private function toImmutable(DateTimeInterface $date): DateTimeImmutable
    {
        return DateTimeImmutable::createFromInterface($date);
    }

    /**
     * @throws ObjectException
     */
    private function toBitrix(DateTimeImmutable $date): BitrixDate
    {
        $format = 'Y-m-d';

        return new BitrixDate(
            $date->format($format),
            $format
        );
    }

    public function isWorkday(DateTimeInterface $date): bool
    {
        return $this->calendar->isWorkday($this->toImmutable($date));
    }

    public function countWorkdays(DateTimeInterface $start, DateTimeInterface $end): int
    {
        return $this->calendar->countWorkdays(
            $this->toImmutable($start),
            $this->toImmutable($end)
        );
    }

    /**
     * @throws ObjectException
     * @throws DateInvalidTimeZoneException
     */
    public function addWorkdays(DateTimeInterface $date, int $days): BitrixDate
    {
        $result = $this->calendar->addWorkdays($this->toImmutable($date), $days);
        return $this->toBitrix($result);
    }

    /**
     * @throws ObjectException
     * @throws DateInvalidTimeZoneException
     */
    public function subtractWorkdays(DateTimeInterface $date, int $days): BitrixDate
    {
        return $this->addWorkdays($date, -$days);
    }

    /**
     * @throws ObjectException
     * @throws DateInvalidTimeZoneException
     */
    public function getClosestWorkday(DateTimeInterface $date, bool $forward = true): BitrixDate
    {
        $result = $this->calendar->getClosestWorkday($this->toImmutable($date), $forward);
        return $this->toBitrix($result);
    }

    /**
     * @throws ObjectException
     */
    public function getWorkdaysIterator(DateTimeInterface $start, DateTimeInterface $end): Generator
    {
        $generator = $this->calendar->getWorkdaysIterator($this->toImmutable($start), $this->toImmutable($end));

        foreach ($generator as $date) {
            yield $this->toBitrix($date);
        }
    }

    /**
     * @throws ObjectException
     */
    public function getFullCalendarIterator(DateTimeImmutable $start, DateTimeImmutable $end): Generator
    {
        $generator = $this->calendar->getFullCalendarIterator($this->toImmutable($start), $this->toImmutable($end));
        /** @var \Kosmosafive\ProductionCalendar\ValueObject\CalendarDay $calendarDay */
        foreach ($generator as $calendarDay) {
            yield new CalendarDay(
                $this->toBitrix($calendarDay->date),
                $calendarDay->type,
                $calendarDay->isStandardWorkday,
                $calendarDay->name,
                $calendarDay->isStandardWeekend,
                ($calendarDay->transferredFrom) ? $this->toBitrix($calendarDay->transferredFrom) : null
            );
        }
    }

    public function hasHolidays(DateTimeImmutable $start, DateTimeImmutable $end): bool
    {
        return $this->calendar->hasHolidays($this->toImmutable($start), $this->toImmutable($end));
    }

    public function clearCache(): void
    {
        $this->calendar->clearCache();
    }
}
