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
    #[DataSet(['fromKilobytes', 2.5, 2560.0], 'kilobytes')]
    #[DataSet(['fromMegabytes', 2.5, 2621440.0], 'megabytes')]
    #[DataSet(['fromGigabytes', 0.0025, 2684354.56], 'gigabytes')]
    #[DataSet(['fromTerabytes', 0.0000025, 2748779.06944], 'terabytes')]
    public function factoryMethodsCreateCorrectByteSize(string $factory, float $input, float $expectedBytes): void
    {
        $size = ByteSize::$factory($input);

        Assert::same($size->toBytes(), $expectedBytes);
    }

    #[Test]
    #[DataSet(['bytes', 10.0, 10.0], 'bytes')]
    #[DataSet(['kb', 2.5, 2560.0], 'kilobytes')]
    #[DataSet(['mb', 2.5, 2621440.0], 'megabytes')]
    #[DataSet(['gb', 0.0025, 2684354.56], 'gigabytes')]
    #[DataSet(['tb', 0.0000025, 2748779.06944], 'terabytes')]
    public function aliasFactoryMethodsCreateCorrectByteSize(string $factory, float $input, float $expectedBytes): void
    {
        $size = ByteSize::$factory($input);

        Assert::same($size->toBytes(), $expectedBytes);
    }

    #[Test]
    public function convertsToAllUnits(): void
    {
        $size = ByteSize::fromBytes(2_621_440);

        Assert::same($size->toBytes(), 2_621_440.0);
        Assert::same($size->toKilobytes(), 2_560.0);
        Assert::same($size->toMegabytes(), 2.5);
        Assert::same($size->toGigabytes(), 0.00244140625);
        Assert::same($size->toTerabytes(), 0.000002384185791015625);
    }

    #[Test]
    public function aliasConversionMethodsConvertToAllUnits(): void
    {
        $size = ByteSize::fromBytes(2_621_440);

        Assert::same($size->toKb(), 2_560.0);
        Assert::same($size->toMb(), 2.5);
        Assert::same($size->toGb(), 0.00244140625);
        Assert::same($size->toTb(), 0.000002384185791015625);
    }

    #[Test]
    #[DataSet([2621440.0, 2, null, '2.5 MB'], 'default label')]
    #[DataSet([2621440.0, 2, 'МБ', '2.5 МБ'], 'custom label')]
    #[DataSet([1024.0, 2, null, '1 KB'], 'trims trailing zeros')]
    public function humanFormatsValueCorrectly(float $bytes, int $precision, ?string $label, string $expected): void
    {
        $size = ByteSize::fromBytes($bytes);

        Assert::same($size->human($precision, $label), $expected);
    }

    #[Test]
    #[DataSet([ByteSizeUnit::TERABYTES, 7, null, '0.0000024 TB'], 'default label')]
    #[DataSet([ByteSizeUnit::TERABYTES, 7, '', '0.0000024'], 'without label')]
    #[DataSet([ByteSizeUnit::MEGABYTES, 2, 'megabytes', '2.5 megabytes'], 'custom label')]
    public function formatFormatsValueCorrectly(
        ByteSizeUnit $unit,
        int $precision,
        ?string $label,
        string $expected,
    ): void
    {
        $size = ByteSize::fromBytes(2621440);

        Assert::same($size->format($unit, $precision, $label), $expected);
    }

    #[Test]
    public function canBeConvertedToString(): void
    {
        $size = ByteSize::fromBytes(2621440);

        Assert::same((string) $size, '2.5 MB');
    }

    #[Test]
    #[DataSet([999.0, '999 B'], 'bytes')]
    #[DataSet([1024.0, '1 KB'], 'kilobyte boundary')]
    #[DataSet([1048576.0, '1 MB'], 'megabyte boundary')]
    #[DataSet([1073741824.0, '1 GB'], 'gigabyte boundary')]
    #[DataSet([1099511627776.0, '1 TB'], 'terabyte boundary')]
    public function humanUsesCorrectUnitAtBoundaries(float $bytes, string $expected): void
    {
        $size = ByteSize::fromBytes($bytes);

        Assert::same($size->human(), $expected);
    }
}
