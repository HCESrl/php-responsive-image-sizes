<?php

namespace Hcesrl\PhpResponsiveImageSizes;

use MabeEnum\Enum;

class BaseSizesType extends Enum
{
    const Custom = 'custom';
    const Standard = 'standard';
    const Granular = 'granular';
}