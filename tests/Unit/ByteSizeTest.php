<?php

declare(strict_types=1);

namespace Tests\Unit;

use ElephantPhp\ByteSize\ByteSize;
use ElephantPhp\ByteSize\ByteSizeUnit;
use Testo\Assert;
use Testo\Data\DataSet;
use Testo\Test;

class ByteSizeTest
{
    #[Test]
    #[DataSet(['fromBytes', 10.0, 10.0], 'bytes')]
    #[DataSet(['fromKilobytes', 2.5, 2500.0], 'kilobytes')]
    #[DataSet(['fromMegabytes', 2.5, 2500000.0], 'megabytes')]
    #[DataSet(['fromGigabytes', 0.0025, 2500000.0], 'gigabytes')]
    #[DataSet(['fromTerabytes', 0.0000025, 2500000.0], 'terabytes')]
    public function factoryMethodsCreateCorrectByteSize(string $factory, float $input, float $expectedBytes): void
    {
        $size = ByteSize::$factory($input);

        Assert::same($size->toBytes(), $expectedBytes);
    }

    #[Test]
    public function convertsToAllUnits(): void
    {
        $size = ByteSize::fromBytes(2_500_000);

        Assert::same($size->toBytes(), 2_500_000.0);
        Assert::same($size->toKilobytes(), 2_500.0);
        Assert::same($size->toMegabytes(), 2.5);
        Assert::same($size->toGigabytes(), 0.0025);
        Assert::same($size->toTerabytes(), 0.0000025);
    }

    #[Test]
    #[DataSet([2500000.0, null, '2.5 MB'], 'default label')]
    #[DataSet([2500000.0, 'МБ', '2.5 МБ'], 'custom label')]
    public function humanFormatsValueCorrectly(float $bytes, ?string $label, string $expected): void
    {
        $size = ByteSize::fromBytes($bytes);

        Assert::same($size->human($label), $expected);
    }

    #[Test]
    #[DataSet([ByteSizeUnit::TERABYTES, 7, null, '0.0000025 TB'], 'default label')]
    #[DataSet([ByteSizeUnit::TERABYTES, 7, '', '0.0000025'], 'without label')]
    #[DataSet([ByteSizeUnit::MEGABYTES, 2, 'megabytes', '2.5 megabytes'], 'custom label')]
    public function formatFormatsValueCorrectly(
        ByteSizeUnit $unit,
        int $precision,
        ?string $label,
        string $expected,
    ): void
    {
        $size = ByteSize::fromBytes(2500000);

        Assert::same($size->format($unit, $precision, $label), $expected);
    }

    #[Test]
    public function canBeConvertedToString(): void
    {
        $size = ByteSize::fromBytes(2500000);

        Assert::same((string) $size, '2.5 MB');
    }

    #[Test]
    #[DataSet([999.0, '999 B'], 'bytes')]
    #[DataSet([1000.0, '1 KB'], 'kilobyte boundary')]
    #[DataSet([1000000.0, '1 MB'], 'megabyte boundary')]
    #[DataSet([1000000000.0, '1 GB'], 'gigabyte boundary')]
    #[DataSet([1000000000000.0, '1 TB'], 'terabyte boundary')]
    public function humanUsesCorrectUnitAtBoundaries(float $bytes, string $expected): void
    {
        $size = ByteSize::fromBytes($bytes);

        Assert::same($size->human(), $expected);
    }
}
