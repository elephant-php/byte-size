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
    #[DataSet(['fromBytes', 10, 10], 'bytes')]
    #[DataSet(['fromKilobytes', 2.5, 2560], 'kilobytes')]
    #[DataSet(['fromMegabytes', 2.5, 2621440], 'megabytes')]
    #[DataSet(['fromGigabytes', 0.0025, 2684355], 'gigabytes')]
    #[DataSet(['fromTerabytes', 0.0000025, 2748779], 'terabytes')]
    public function factoryMethodsCreateCorrectByteSize(string $factory, int|float $input, int $expectedBytes): void
    {
        $size = ByteSize::$factory($input);

        Assert::same($size->toBytes(), $expectedBytes);
    }

    #[Test]
    #[DataSet(['bytes', 10, 10], 'bytes')]
    #[DataSet(['kb', 2.5, 2560], 'kilobytes')]
    #[DataSet(['mb', 2.5, 2621440], 'megabytes')]
    #[DataSet(['gb', 0.0025, 2684355], 'gigabytes')]
    #[DataSet(['tb', 0.0000025, 2748779], 'terabytes')]
    public function aliasFactoryMethodsCreateCorrectByteSize(string $factory, int|float $input, int $expectedBytes): void
    {
        $size = ByteSize::$factory($input);

        Assert::same($size->toBytes(), $expectedBytes);
    }

    #[Test]
    public function convertsToAllUnits(): void
    {
        $size = ByteSize::fromBytes(2_621_440);

        Assert::same($size->toBytes(), 2_621_440);
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
    #[DataSet([2621440, 2, null, '2.5 MB'], 'default label')]
    #[DataSet([2621440, 2, 'МБ', '2.5 МБ'], 'custom label')]
    #[DataSet([1024, 2, null, '1 KB'], 'trims trailing zeros')]
    public function humanFormatsValueCorrectly(int $bytes, int $precision, ?string $label, string $expected): void
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
    public function canFormatRealFileSizeFromFilesystem(): void
    {
        $bytes = filesize(__DIR__ . '/../../elephant-php.webp');

        Assert::notSame($bytes, false);

        $size = ByteSize::fromBytes($bytes);

        Assert::same($bytes, 2296); // Actual image size
        Assert::same($size->human(), '2.24 KB');
        Assert::same($size->human(0), '2 KB');
        Assert::same($size->human(0, 'kilobytes'), '2 kilobytes');
    }

    #[Test]
    #[DataSet([999, '999 B'], 'bytes')]
    #[DataSet([1024, '1 KB'], 'kilobyte boundary')]
    #[DataSet([1048576, '1 MB'], 'megabyte boundary')]
    #[DataSet([1073741824, '1 GB'], 'gigabyte boundary')]
    #[DataSet([1099511627776, '1 TB'], 'terabyte boundary')]
    public function humanUsesCorrectUnitAtBoundaries(int $bytes, string $expected): void
    {
        $size = ByteSize::fromBytes($bytes);

        Assert::same($size->human(), $expected);
    }
}
