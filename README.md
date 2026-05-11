<p align="center">
    <a href="https://github.com/elephant-php/byte-size" target="_blank">
        <img src="elephant-php.webp" height="256" alt="elephant-php">
    </a>
    <h1 align="center">ByteSize</h1>
    <p align="center">A small value object for working with file sizes in a clean and predictable way.</p>
    <p align="center">
        <a href="https://packagist.org/packages/elephant-php/byte-size"><img src="https://img.shields.io/packagist/v/elephant-php/byte-size.svg" alt="Latest Version"></a>
        <a href="./LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
        <a href="https://www.php.net/releases/8.1/en.php"><img src="https://img.shields.io/badge/PHP-8.1%2B-777bb4.svg" alt="PHP 8.1+"></a>
    </p>
    <br>
</p>

It uses `1024` based conversion and gives you a simple API to:

- create sizes from bytes, KB, MB, GB, or TB
- convert values between units
- format values for UI or CLI output
- generate human-readable file size strings

## Installation

```bash
composer require elephant-php/byte-size
```

## Requirements

- PHP `8.1` or higher

## Quick example

```php
use ElephantPhp\ByteSize\ByteSize;
use ElephantPhp\ByteSize\ByteSizeUnit;

$size = ByteSize::fromBytes(2_621_440);

echo $size->toMegabytes();                      // 2.5
echo $size->format(ByteSizeUnit::MEGABYTES);    // 2.5 MB
echo $size->human();                            // 2.5 MB
echo $size->human(0);                           // 2 MB
echo $size->human(0, 'Megabytes');              // 2 Megabytes
echo (string) $size;                            // 2.5 MB
```

## Real-world example

```php
use ElephantPhp\ByteSize\ByteSize;

$bytes = filesize('/path/to/archive.zip');
$size = ByteSize::fromBytes($bytes);

echo $size->human(); // e.g. "14.8 MB"
```

## Creation

```php
use ElephantPhp\ByteSize\ByteSize;

ByteSize::fromBytes(2621440);
ByteSize::fromKilobytes(2560);
ByteSize::fromMegabytes(2.5);
ByteSize::fromGigabytes(0.0025);
ByteSize::fromTerabytes(0.0000025);
```

Short aliases are available too:

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

Short aliases:

```php
$size->toKb();
$size->toMb();
$size->toGb();
$size->toTb();
```

## Formatting

Use `format()` when you want an explicit unit.

```php
use ElephantPhp\ByteSize\ByteSize;
use ElephantPhp\ByteSize\ByteSizeUnit;

$size = ByteSize::fromBytes(2_621_440);

echo $size->format(ByteSizeUnit::TERABYTES, 7);       // 0.0000024 TB
echo $size->format(ByteSizeUnit::TERABYTES, 7, '');   // 0.0000024
echo $size->format(ByteSizeUnit::MEGABYTES, 2);       // 2.5 MB
echo $size->format(ByteSizeUnit::MEGABYTES, 2, 'МБ'); // 2.5 МБ
```

- `$precision` controls the number of decimal places before trailing zeros are trimmed
- `$label` overrides the default unit label
- passing `''` as label returns only the numeric value

## Human-readable output

Use `human()` when you want `ByteSize` to choose the most suitable unit automatically.

```php
use ElephantPhp\ByteSize\ByteSize;

$size = ByteSize::fromBytes(2_621_440);

echo $size->human();              // 2.5 MB
echo $size->human(2, 'MEGABYTE'); // 2.5 MEGABYTE
echo (string) $size;              // 2.5 MB
```

Examples:

```php
ByteSize::fromBytes(999)->human();                 // 999 B
ByteSize::fromBytes(1024)->human();                // 1 KB
ByteSize::fromBytes(1048576)->human();             // 1 MB
ByteSize::fromBytes(1073741824)->human();          // 1 GB
ByteSize::fromBytes(1099511627776)->human();       // 1 TB
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
