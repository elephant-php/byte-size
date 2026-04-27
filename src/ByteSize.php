<?php

declare(strict_types=1);

namespace ElephantPhp\ByteSize;

final class ByteSize
{
    private const UNIT_BYTES = 'B';
    private const UNIT_KILOBYTES = 'KB';
    private const UNIT_MEGABYTES = 'MB';
    private const UNIT_GIGABYTES = 'GB';
    private const UNIT_TERABYTES = 'TB';

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

    public function toHuman(): string
    {
        if ($this->byteSize >= self::BYTES_IN_TERABYTE) {
            return $this->toFormatted(self::UNIT_TERABYTES);
        }

        if ($this->byteSize >= self::BYTES_IN_GIGABYTE) {
            return $this->toFormatted(self::UNIT_GIGABYTES);
        }

        if ($this->byteSize >= self::BYTES_IN_MEGABYTE) {
            return $this->toFormatted(self::UNIT_MEGABYTES);
        }

        if ($this->byteSize >= self::BYTES_IN_KILOBYTE) {
            return $this->toFormatted(self::UNIT_KILOBYTES);
        }

        return $this->toFormatted(self::UNIT_BYTES, 0);
    }

    public function __toString(): string
    {
        return $this->toHuman();
    }

    public function toFormatted(string $unit, int $precision = 2): string
    {
        $normalizedUnit = strtoupper($unit);
        $value = match ($normalizedUnit) {
            self::UNIT_BYTES => $this->toBytes(),
            self::UNIT_KILOBYTES => $this->toKilobytes(),
            self::UNIT_MEGABYTES => $this->toMegabytes(),
            self::UNIT_GIGABYTES => $this->toGigabytes(),
            self::UNIT_TERABYTES => $this->toTerabytes(),
            default => throw new \InvalidArgumentException('Unsupported unit: ' . $unit),
        };

        $formattedValue = number_format($value, $precision, '.', '');
        $trimmedValue = $precision > 0
            ? rtrim(rtrim($formattedValue, '0'), '.')
            : $formattedValue;

        return $trimmedValue . ' ' . $normalizedUnit;
    }
}
