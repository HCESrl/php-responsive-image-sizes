<?php

namespace Hcesrl\PhpResponsiveImageSizes;

class ResponsiveImagesSizes
{
    public static function getResponsiveSizes(
        DeviceType $deviceType,
        BaseSizes $baseSizes,
        int $sourceImageWidth = 3220,
        int $widthOnPage = 100,
        int $topSize = 1920) : array
    {
        $sizes = self::collectSizes($deviceType, $baseSizes);
        $sizes = self::checkTopSize($sizes, $topSize);

        // get list of sizes based on width on page
        $sizes = self::buildSizeList($sizes, $widthOnPage);

        // check that we're not producing images that are bigger than the source image, to avoid unnecessary upsampling
        $sizes = self::checkMaxImageSize($sizes, $sourceImageWidth);

        return $sizes;
    }

    private static function collectSizes(DeviceType $deviceType, BaseSizes $baseSizes) : array
    {
        $res = [];
        switch ($deviceType->getValue()) {
            case DeviceType::All; // images from all types, without duplicates
                $res = array_unique(call_user_func_array('array_merge', $baseSizes->getSizes()));
            break;
            case DeviceType::Mobile: // tablet portrait and smartphone
                $res = array_merge($baseSizes->getSizes(DeviceType::TabletPortrait()), $baseSizes->getSizes(DeviceType::Smartphone()));
            break;
            default:
                $res = $baseSizes->getSizes($deviceType);
            break;
        }
        sort($res, SORT_NUMERIC);
        return $res;
    }

    private static function checkTopSize(array $sizes, int $topSize) : array {
        return array_filter($sizes, function ($item) use ($topSize) { return $item <= $topSize; });
    }

    private static function checkMaxImageSize (array $sizes, $sourceImageWidth) : array
    {
        return array_filter($sizes, function ($item) use ($sourceImageWidth) { return $item <= $sourceImageWidth; });
    }

    private static function buildSizeList ($sizes, $widthOnPage) : array
    {
        return array_map(function ($item) use ($widthOnPage) { return round(($item / 100) * $widthOnPage); }, $sizes);
    }
}