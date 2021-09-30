<?php

namespace Hcesrl\PhpResponsiveImageSizes;

class BaseSizes
{
    /**
     * Base sizes to create images on
     */
    const standardBaseSizes = [
        DeviceType::Desktop => [
          2880,
          2560,
          1920,
          1600, // same as tablet portrait first
          1440,
          1366,
          1024
        ],
        DeviceType::TabletPortrait => [
          1600, // same as second entry for desktop
          1024,
          768
        ],
        DeviceType::Smartphone => [1242, 828, 768, 640]
    ];

    /**
     * Granular base sizes (more images, more precise, higher top image size)
     */
    const granularBaseSizes = [
        DeviceType::Desktop => [
          2880,
          2560, //2K iMac
          2048, // iPad Landscape
          1920,
          1680,
          1440,
          1366,
          1280,
          1024
        ],
        DeviceType::TabletPortrait => [
          2048, // iPad Pro
          1536,
          1024,
          768
        ],
        DeviceType::Smartphone => [
          1242,
          828,
          750,
          720,
          640
        ]
    ];

    private $configuredSizes = [];

    public function __construct(BaseSizesType $baseSizesType = null, array $customSizes = []) {
        switch ($baseSizesType->getValue()) {
            case BaseSizesType::Custom:
                $this->setSizes($customSizes);
                break;
            case BaseSizesType::Granular:
                $this->configuredSizes = self::granularBaseSizes;
                break;
            default: // BaseSizesType::Standard
                $this->configuredSizes = self::standardBaseSizes;
                break;
        }
    }

    public function getSizes(DeviceType $deviceType = null) : array
    {
        if (count($this->configuredSizes) === 0) {
            throw new \Exception('Custom sizes not set yet, please read the docs.');
        }
        return isset($deviceType) ? $this->configuredSizes[$deviceType->getValue()] : $this->configuredSizes;
    }

    public function setSizes(array $customSizes) : void
    {
        if (array_key_exists(DeviceType::Desktop, $customSizes) && count($customSizes[DeviceType::Desktop]) > 0 &&
            array_key_exists(DeviceType::TabletPortrait, $customSizes) && count($customSizes[DeviceType::TabletPortrait]) > 0 &&
            array_key_exists(DeviceType::Smartphone, $customSizes) && count($customSizes[DeviceType::Smartphone]) > 0) {
            $this->configuredSizes = $customSizes;
        } else {
            throw new \Exception('Custom sizes structure is not valid, expecting all smartphone, tabletPortrait and desktop sizes. Please read the docs.');
        }
    }
}