<?php

namespace Hcesrl\PhpResponsiveImageSizes;

class ResponsiveImagesSizes
{
    /**
     * @param DeviceType $deviceType   whether to generate sizes for desktop, tabletPortrait, smartphone or all
     * @param BaseSizes $baseSizes     BaseSizes instance, with granular, standard, or custom sizes (the first has more precise resolutions and creates more images)
     * @param int $sourceImageWidth    the width of the original image, in pixels (to avoid upscaling)
     * @param int $widthOnPage         the actual width of the image on the page, in vw (% of width)
     * @param int $topSize             the highest resolution to generate (fullHD is default, but if you need to go above that provide pixels)
     * @return array                   array of image sizes to produce
     */

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

    /**
     * Return all sizes for the desired mode
     *
     * @param DeviceType $deviceType
     * @param BaseSizes $baseSizes
     * @return array
     * @throws \Exception             if BaseSizes is declared as Custom and custom sizes aren't declared
     */
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

    /**
     * Make sure we do not build images that are wider than the top required size
     *
     * @param array $sizes
     * @param int $topSize
     * @return array
     */
    private static function checkTopSize(array $sizes, int $topSize) : array {
        return array_filter($sizes, function ($item) use ($topSize) { return $item <= $topSize; });
    }

    /**
     * Make sure we do not build images that are wider than the original, once we get the final image sizes
     *
     * @param array $sizes
     * @param $sourceImageWidth
     * @return array
     */
    private static function checkMaxImageSize (array $sizes, $sourceImageWidth) : array
    {
        return array_filter($sizes, function ($item) use ($sourceImageWidth) { return $item <= $sourceImageWidth; });
    }

    /**
     * adapts the standard size list to the current requirement in terms of space
     *
     * @param $sizes
     * @param $widthOnPage
     * @return array
     */
    private static function buildSizeList ($sizes, $widthOnPage) : array
    {
        return array_map(function ($item) use ($widthOnPage) { return round(($item / 100) * $widthOnPage); }, $sizes);
    }
}