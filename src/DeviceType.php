<?php

namespace Hcesrl\PhpResponsiveImageSizes;

use MabeEnum\Enum;

class DeviceType extends Enum
{
    const Desktop = 'desktop';
    const TabletPortrait = 'tabletPortrait';
    const Mobile = 'mobile';
    const Smartphone = 'smartphone';
    const All = 'all';
}