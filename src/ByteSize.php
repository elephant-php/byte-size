<?php

declare(strict_types=1);

namespace ElephantPhp\ByteSize;

final class ByteSize
{
    private const float BYTES_IN_KILOBYTE = 1024;
    private const float BYTES_IN_MEGABYTE = 1024 * 1024;
    private const float BYTES_IN_GIGABYTE = 1024 * 1024 * 1024;
    private const float BYTES_IN_TERABYTE = 1024 * 1024 * 1024 * 1024;

    private function __construct(
        private readonly int $bytes,
    ) {
        if ($bytes < 0) {
            throw new \InvalidArgumentException('Byte size cannot be negative.');
        }
    }

    public static function fromBytes(int $bytes): self
    {
        return new self($bytes);
    }

    public static function fromKilobytes(int|float $kilobytes): self
    {
        return new self((int) round($kilobytes * self::BYTES_IN_KILOBYTE));
    }

    public static function fromMegabytes(int|float $megabytes): self
    {
        return new self((int) round($megabytes * self::BYTES_IN_MEGABYTE));
    }

    public static function fromGigabytes(int|float $gigabytes): self
    {
        return new self((int) round($gigabytes * self::BYTES_IN_GIGABYTE));
    }

    public static function fromTerabytes(int|float $terabytes): self
    {
        return new self((int) round($terabytes * self::BYTES_IN_TERABYTE));
    }

    public static function bytes(int $bytes): self
    {
        return new self($bytes);
    }

    public static function kb(int|float $kilobytes): self
    {
        return new self((int) round($kilobytes * self::BYTES_IN_KILOBYTE));
    }

    public static function mb(int|float $megabytes): self
    {
        return new self((int) round($megabytes * self::BYTES_IN_MEGABYTE));
    }

    public static function gb(int|float $gigabytes): self
    {
        return new self((int) round($gigabytes * self::BYTES_IN_GIGABYTE));
    }

    public static function tb(int|float $terabytes): self
    {
        return new self((int) round($terabytes * self::BYTES_IN_TERABYTE));
    }

    public function toBytes(): int
    {
        return $this->bytes;
    }

    public function toKilobytes(): float
    {
        return $this->bytes / self::BYTES_IN_KILOBYTE;
    }

    public function toMegabytes(): float
    {
        return $this->bytes / self::BYTES_IN_MEGABYTE;
    }

    public function toGigabytes(): float
    {
        return $this->bytes / self::BYTES_IN_GIGABYTE;
    }

    public function toTerabytes(): float
    {
        return $this->bytes / self::BYTES_IN_TERABYTE;
    }

    public function toKb(): float
    {
        return $this->toKilobytes();
    }

    public function toMb(): float
    {
        return $this->toMegabytes();
    }

    public function toGb(): float
    {
        return $this->toGigabytes();
    }

    public function toTb(): float
    {
        return $this->toTerabytes();
    }

    public function human(int $precision = 2, ?string $label = null): string
    {
        $unit = match (true) {
            $this->bytes >= self::BYTES_IN_TERABYTE => ByteSizeUnit::TERABYTES,
            $this->bytes >= self::BYTES_IN_GIGABYTE => ByteSizeUnit::GIGABYTES,
            $this->bytes >= self::BYTES_IN_MEGABYTE => ByteSizeUnit::MEGABYTES,
            $this->bytes >= self::BYTES_IN_KILOBYTE => ByteSizeUnit::KILOBYTES,
            default => ByteSizeUnit::BYTES,
        };

        $precision = $unit === ByteSizeUnit::BYTES ? 0 : $precision;

        return $this->format($unit, $precision, $label);
    }

    public function __toString(): string
    {
        return $this->human();
    }

    public function format(ByteSizeUnit $unit, int $precision = 2, ?string $label = null): string
    {
        $value = match ($unit) {
            ByteSizeUnit::BYTES     => $this->toBytes(),
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

        return $trimmedValue . ' ' . ($label ?? $unit->value);
    }
}
