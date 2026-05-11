<p align="center">
    <a href="https://github.com/elephant-php/byte-size" target="_blank">
        <img src="elephant-php.webp" height="256" alt="elephant-php">
    </a>
    <h1 align="center">ByteSize</h1>
    <p align="center">A tiny immutable PHP value object for file size conversion and human-readable formatting.</p>
    <p align="center">
        <a href="https://packagist.org/packages/elephant-php/byte-size"><img src="https://img.shields.io/packagist/v/elephant-php/byte-size.svg" alt="Latest Version"></a>
        <a href="./LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
        <a href="https://www.php.net/releases/8.1/en.php"><img src="https://img.shields.io/badge/PHP-8.1%2B-777bb4.svg" alt="PHP 8.1+"></a>
    </p>
    <br>
</p>

`ByteSize` is a small PHP value object for working with file sizes in a clean and predictable way.

It gives you a simple API to convert raw bytes into higher units, format values for UI or CLI output, and generate readable file-size strings without mixing conversion logic into your application code.

`ByteSize` helps you:

- create a size from bytes, KB, MB, GB, or TB
- convert the value to any supported unit
- format values for UI and logs
- generate human-readable output

## Why ByteSize?

- immutable and predictable value object
- practical file-size conversion based on `1024`
- clean formatting API with customizable labels
- useful for file sizes, storage usage, logs, CLI output, and UI rendering

The library uses binary file-size units:

- `1 KB = 1024 B`
- `1 MB = 1024 KB`
- `1 GB = 1024 MB`
- `1 TB = 1024 GB`

## Installation

```bash
composer require elephant-php/byte-size
```

## Requirements

- PHP `8.1` or higher

## Basic usage

```php
use ElephantPhp\ByteSize\ByteSize;

$size = ByteSize::fromBytes(2_621_440);

echo $size->toBytes();      // 2621440
echo $size->toKilobytes();  // 2560
echo $size->toMegabytes();  // 2.5
echo $size->toGigabytes();  // 0.00244140625
echo $size->toTerabytes();  // 0.000002384185791015625
```

## Creation

```php
use ElephantPhp\ByteSize\ByteSize;

ByteSize::fromBytes(2621440);
ByteSize::fromKilobytes(2560);
ByteSize::fromMegabytes(2.5);
ByteSize::fromGigabytes(0.0025);   // 2684354.56 bytes
ByteSize::fromTerabytes(0.0000025); // 2748779.06944 bytes
```

Aliases are also available:

```php
ByteSize::bytes(2621440);
ByteSize::kb(2560);
ByteSize::mb(2.5);
ByteSize::gb(0.0025);
ByteSize::tb(0.0000025);
```

## Conversion

```php
use ElephantPhp\ByteSize\ByteSize;

$size = ByteSize::fromBytes(2_621_440);

$size->toBytes();      // 2621440
$size->toKilobytes();  // 2560
$size->toMegabytes();  // 2.5
$size->toGigabytes();  // 0.00244140625
$size->toTerabytes();  // 0.000002384185791015625
```

Short aliases are also available:

```php
$size->toKb(); // 2560
$size->toMb(); // 2.5
$size->toGb(); // 0.00244140625
$size->toTb(); // 0.000002384185791015625
```

## Formatting

Use `format()` when you want an explicit unit.

```php
use ElephantPhp\ByteSize\ByteSize;
use ElephantPhp\ByteSize\ByteSizeUnit;

$size = ByteSize::fromBytes(2_621_440);

echo $size->format(ByteSizeUnit::TERABYTES, 7);        // 0.0000024 TB
echo $size->format(ByteSizeUnit::TERABYTES, 7, '');    // 0.0000024
echo $size->format(ByteSizeUnit::MEGABYTES, 2);        // 2.5 MB
echo $size->format(ByteSizeUnit::MEGABYTES, 2, 'МБ');  // 2.5 МБ
echo $size->format(ByteSizeUnit::MEGABYTES, 2, 'MBs'); // 2.5 MBs
```

Arguments:

- `$unit` is the unit you want to format to
- `$precision` is the maximum number of decimal places before trimming trailing zeros
- `$label` overrides the default unit label

Label behavior:

- `null` uses the default label from `ByteSizeUnit`
- `''` returns only the numeric value
- any other string is used as a custom label

## Human-readable output

Use `human()` when you want the library to choose the most suitable unit automatically.

```php
use ElephantPhp\ByteSize\ByteSize;

$size = ByteSize::fromBytes(2_621_440);

echo $size->human();      // 2.5 MB
echo $size->human(2, 'МБ');  // 2.5 МБ
echo (string) $size;      // 2.5 MB
```

Examples of automatic unit switching:

```php
ByteSize::fromBytes(999)->human();                // 999 B
ByteSize::fromBytes(1024)->human();               // 1 KB
ByteSize::fromBytes(1048576)->human();            // 1 MB
ByteSize::fromBytes(1073741824)->human();         // 1 GB
ByteSize::fromBytes(1099511627776)->human();      // 1 TB
```

## Available units

```php
use ElephantPhp\ByteSize\ByteSizeUnit;

ByteSizeUnit::BYTES;
ByteSizeUnit::KILOBYTES;
ByteSizeUnit::MEGABYTES;
ByteSizeUnit::GIGABYTES;
ByteSizeUnit::TERABYTES;
```

## License

MIT License

Please see [`LICENSE`](./LICENSE.md) for more information.
