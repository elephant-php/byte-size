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

    public function toHuman(): string
    {
        if ($this->byteSize >= self::BYTES_IN_TERABYTE) {
            return $this->formatHumanValue($this->toTerabytes()) . ' TB';
        }

        if ($this->byteSize >= self::BYTES_IN_GIGABYTE) {
            return $this->formatHumanValue($this->toGigabytes()) . ' GB';
        }

        if ($this->byteSize >= self::BYTES_IN_MEGABYTE) {
            return $this->formatHumanValue($this->toMegabytes()) . ' MB';
        }

        if ($this->byteSize >= self::BYTES_IN_KILOBYTE) {
            return $this->formatHumanValue($this->toKilobytes()) . ' KB';
        }

        return number_format($this->toBytes(), 0, '.', '') . ' B';
    }

    public function __toString(): string
    {
        return $this->toHuman();
    }

    private function formatHumanValue(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }
}
