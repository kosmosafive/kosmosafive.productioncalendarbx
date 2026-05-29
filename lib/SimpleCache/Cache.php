<?php

declare(strict_types=1);

namespace Kosmosafive\ProductionCalendarBx\SimpleCache;

use DateInterval;
use DateTimeImmutable;
use Kosmosafive\ProductionCalendar\ValueObject\Day;
use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface
{
    public function __construct(
        protected string $folder
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (!file_exists($this->folder . $key)) {
            return $default;
        }

        return unserialize(
            file_get_contents($this->folder . $key),
            [
                'allowed_classes' => [
                    Day::class,
                    DateTimeImmutable::class,
                ],
            ]
        );
    }

    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        if (
            !file_exists($this->folder)
            && !mkdir($concurrentDirectory = $this->folder, 0o777, true)
            && !is_dir($concurrentDirectory)
        ) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        file_put_contents($this->folder . $key, serialize($value));
        return true;
    }

    public function delete(string $key): bool
    {
        if (file_exists($this->folder . $key)) {
            unlink($this->folder . $key);
        }

        return true;
    }

    public function clear(): bool
    {
        if (is_dir($this->folder)) {
            unlink($this->folder);
        }
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->get($key, $default);
        }
    }

    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has(string $key): bool
    {
        return file_exists($this->folder . $key);
    }
}
