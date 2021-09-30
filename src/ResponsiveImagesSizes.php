<?php

namespace Hcesrl\PhpResponsiveImageSizes;

class ResponsiveImagesSizes
{
    public function getResponsiveSizes(
        DeviceType $deviceType,
        BaseSizes $baseSizes,
        int $sourceImageWidth = 3220,
        int $widthOnPage = 100,
        int $topSize = 1920) : array
    {
        $sizes = $this->collectSizes($deviceType, $baseSizes);
        $sizes = $this->checkTopSize($sizes, $topSize);

        // get list of sizes based on width on page
        $sizes = $this->buildSizeList($sizes, $widthOnPage);

        // check that we're not producing images that are bigger than the source image, to avoid unnecessary upsampling
        $sizes = $this->checkMaxImageSize($sizes, $sourceImageWidth);

        return $sizes;
    }

    private function collectSizes($deviceType, BaseSizes $baseSizes) : array
    {
        $res = [];
        switch ($deviceType) {
            case DeviceType::All; // images from all types, without duplicates
                $res = call_user_func_array('array_merge', $baseSizes->getSizes());
            break;
            case DeviceType::Mobile: // tablet portrait and smartphone
                $res = array_merge($baseSizes->getSizes(DeviceType::TabletPortrait()), $baseSizes->getSizes(DeviceType::Smartphone()));
            break;
            default:
                $res = $baseSizes->getSizes($deviceType);
            break;
        }
        asort($res, SORT_NUMERIC);
        return $res;
    }

    private function checkTopSize(array $sizes, int $topSize) : array {
        return array_filter($sizes, function ($item) use ($topSize) { return $item <= $topSize; });
    }

    private function checkMaxImageSize (array $sizes, $sourceImageWidth) : array
    {
        return array_filter($sizes, function ($item) use ($sourceImageWidth) { return $item <= $sourceImageWidth; });
    }

    private function buildSizeList ($sizes, $widthOnPage) : array
    {
        return array_map(function ($item) use ($widthOnPage) { return round(($item / 100) * $widthOnPage); }, $sizes);
    }
}