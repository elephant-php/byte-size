<?php

declare(strict_types=1);

namespace ElephantPhp\ByteSize;

enum ByteSizeUnit: string
{
    case BYTES     = 'B';
    case KILOBYTES = 'KB';
    case MEGABYTES = 'MB';
    case GIGABYTES = 'GB';
    case TERABYTES = 'TB';
}
