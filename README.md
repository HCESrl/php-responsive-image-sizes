# Responsive image sizes library written in PHP

## Installation

Please add these lines to your `composer.json` file:
```
{
  [...]
  "repositories": [
    [...]
    {
      "type": "vcs",
      "url": "https://github.com/HCESrl/php-responsive-image-sizes"
    }
  ],
  "require": {
    [...]
    "hcesrl/php-responsive-image-sizes": "dev-master"
  }
```

then you can run `composer install` to install the library.

## Usage
Please refer to the original javascript library documentation for further informations. 
Here is a basic example:

```
<?php
use Hcesrl\PhpResponsiveImageSizes\BaseSizes;
use Hcesrl\PhpResponsiveImageSizes\DeviceType;
use Hcesrl\PhpResponsiveImageSizes\ResponsiveImagesSizes;

require 'vendor/autoload.php';

var_dump(ResponsiveImagesSizes::getResponsiveSizes(DeviceType::All(), new BaseSizes(BaseSizesType::Granular())));
```

`ResponsiveImagesSizes::getResponsiveSizes` function accepts following parameters:

* **$deviceType** enum value: one of the available Enum DeviceType values (based on `marc-mabe/php-enum` library);
* **BaseSizes** class instance: the constructor expects one of the available Enum BaseSizesType values (based on `marc-mabe/php-enum` library). If you choose `BaseSizesType::Custom()` value, a second parameter is expected (see below);
* **$sourceImageWidth** integer, default is 3220, see js library docs for further informations;
* **$widthOnPage** integer, default is 100, see js library docs for further informations;
* **$topSize** integer, default is 1920, see js library docs for further informations;

if you want to use a custom set of BaseSizes, you should declare an array that matches the following schema, and use it as second BaseSizes class constructor's argument:
```
[
    'desktop' => [
        -- values --
    ],
    'tabletPortrait => [
        -- values --
    ],
    'smartphone' => [
        -- values --
    ]
]
```

Example:
```
$customSizes = [
    DeviceType::Desktop => [
      1440,
      1280,
      1024
    ],
    DeviceType::TabletPortrait => [
      1024,
      768
    ],
    DeviceType::Smartphone => [
      828,
      750,
      720,
      640
    ]
];
var_dump(ResponsiveImagesSizes::getResponsiveSizes(DeviceType::All(), new BaseSizes(BaseSizesType::Custom(), $customSizes)));
```

Output sample:
```
(
    [0] => 1024
    [1] => 1280
    [2] => 1440
)
```