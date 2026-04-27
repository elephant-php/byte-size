<?php

declare(strict_types=1);

namespace ElephantPhp\ByteSize;

final class ByteSize
{
    private const BYTES_IN_KILOBYTE = 1000;
    private const BYTES_IN_MEGABYTE = 1000_000;
    private const BYTES_IN_GIGABYTE = 1000_000_000;
    private const BYTES_IN_TERABYTE = 1000_000_000_000;

    private function __construct(private readonly float $byteSize)
    {
    }

    public static function fromBytes(float $bytes): self
    {
        return new self($bytes);
    }

    public static function fromKilobytes(float $kilobytes): self
    {
        return new self($kilobytes * self::BYTES_IN_KILOBYTE);
    }

    public static function fromMegabytes(float $megabytes): self
    {
        return new self($megabytes * self::BYTES_IN_MEGABYTE);
    }

    public static function fromGigabytes(float $gigabytes): self
    {
        return new self($gigabytes * self::BYTES_IN_GIGABYTE);
    }

    public static function fromTerabytes(float $terabytes): self
    {
        return new self($terabytes * self::BYTES_IN_TERABYTE);
    }

    public function toKilobytes(): float
    {
        return $this->byteSize / self::BYTES_IN_KILOBYTE;
    }

    public function toMegabytes(): float
    {
        return $this->byteSize / self::BYTES_IN_MEGABYTE;
    }

    public function toGigabytes(): float
    {
        return $this->byteSize / self::BYTES_IN_GIGABYTE;
    }

    public function toTerabytes(): float
    {
        return $this->byteSize / self::BYTES_IN_TERABYTE;
    }

    public function toBytes(): float
    {
        return $this->byteSize;
    }

    public function human(?string $label = null): string
    {
        if ($this->byteSize >= self::BYTES_IN_TERABYTE) {
            return $this->format(ByteSizeUnit::TERABYTES, 2, $label);
        }

        if ($this->byteSize >= self::BYTES_IN_GIGABYTE) {
            return $this->format(ByteSizeUnit::GIGABYTES, 2, $label);
        }

        if ($this->byteSize >= self::BYTES_IN_MEGABYTE) {
            return $this->format(ByteSizeUnit::MEGABYTES, 2, $label);
        }

        if ($this->byteSize >= self::BYTES_IN_KILOBYTE) {
            return $this->format(ByteSizeUnit::KILOBYTES, 2, $label);
        }

        return $this->format(ByteSizeUnit::BYTES, 0, $label);
    }

    public function __toString(): string
    {
        return $this->human();
    }

    public function format(ByteSizeUnit|string $unit, int $precision = 2, ?string $label = null): string
    {
        $normalizedUnit = $unit instanceof ByteSizeUnit
            ? $unit
            : ByteSizeUnit::from(strtoupper($unit));
        $value = match ($normalizedUnit) {
            ByteSizeUnit::BYTES => $this->toBytes(),
            ByteSizeUnit::KILOBYTES => $this->toKilobytes(),
            ByteSizeUnit::MEGABYTES => $this->toMegabytes(),
            ByteSizeUnit::GIGABYTES => $this->toGigabytes(),
            ByteSizeUnit::TERABYTES => $this->toTerabytes(),
        };

        $formattedValue = number_format($value, $precision, '.', '');
        $trimmedValue = $precision > 0
            ? rtrim(rtrim($formattedValue, '0'), '.')
            : $formattedValue;

        if ($label === '') {
            return $trimmedValue;
        }

        return $trimmedValue . ' ' . ($label ?? $normalizedUnit->value);
    }
}
