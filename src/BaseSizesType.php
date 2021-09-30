<?php

namespace Hcesrl\PhpResponsiveImageSizes;

use MabeEnum\Enum;

abstract class BaseSizesType extends Enum
{
    const Custom = 'custom';
    const Standard = 'standard';
    const Granular = 'granular';
}