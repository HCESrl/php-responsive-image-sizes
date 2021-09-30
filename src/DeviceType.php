<?php

namespace Hcesrl\PhpResponsiveImageSizes;

use MabeEnum\Enum;

abstract class DeviceType extends Enum
{
    const Desktop = 'desktop';
    const TabletPortrait = 'tabletPortrait';
    const Mobile = 'mobile';
    const Smartphone = 'smartphone';
    const All = 'all';
}