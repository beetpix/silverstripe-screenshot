# SilverStripe Screenshot Module

[![Build Status](http://img.shields.io/travis/badges/badgerbadgerbadger.svg?style=flat-square)](https://travis-ci.org/badges/badgerbadgerbadger)

Simple SDK with helpers to screenshot websites and save or download to PDF or JPEG via printscreenapi.com

There's a free plan which is perfect for small projects and allows for 100 fresh screenshots per month with unlimited access to cached images and PDFs.

- [Get an API key](https://printscreenapi.com/plans)
- [Documentation](https://printscreenapi.com/api)

## Requirements

* SilverStripe Framework 4.0+

## Installation
> Via composer
* Run `composer require beetpix/silverstripe-screenshot`
* Run `/dev/build` in your browser
> Manually
* Download and extract files into `printscreenapi/` in your project's root folder
* Run `/dev/build` in your browser

## Configure API key

Add your API key via YAML or environment file:

YAML
```yaml
Beetpix\PrintScreen
  api_key: YOUR_API_KEY_HERE
```

Environment (.env)
```
PRINTSCREENAPI_KEY="YOUR_API_KEY_HERE"
```

## Example usage

Get image screenshot URL:
```php
$url = Beetpix\PrintScreen::inst()
    ->fullPage()
    ->getImage('https://www.example.com');
```
Download as PDF:
```php
Beetpix\PrintScreen::inst()
    ->downloadPDF('https://www.example.com');
```
Additional options, flush cache and read raw result:
```php
$inst = Beetpix\PrintScreen::inst()
    ->device('phone-landscape')
    ->click('.btn-accept-cookies')
    ->fullPage()
    ->flush();

// Get URL
$url = $inst->getImage('https://www.example.com');

// Download image
$inst->downloadImage('https://www.example.com');

// Read raw result
print_r($inst->getResult());
```

## Available methods

For detailed explanation about each parameter, please check out the the documentation at:
https://printscreenapi.com/api/

| Method | Parameters | Description | API parameter |
| ------ | ------ | ------ | ------ |
| getImage | string $target = null | Return screenshot image URL | - |
| getPDF | string $target = null | Return screenshot PDF URL | - |
| downloadPDF | string $target = null, string $filename = null | Download PDF | - |
| downloadImage | string $target = null, string $filename = null | Download image | - |
| width | int $value | Viewport width | width |
| height | int $value | Viewport height | height |
| scrollTo | string $value | Scroll to element | scrollto |
| click | string $value | Click on element | click |
| flush | - | Force fresh screenshot | flush |
| fullPage | - | Take a full page screenshot | fullpage |
| crop | int $x, int $y, int $width, int $height | Crop selected area in pixels | crop |
| device | string $value | Set device (default is "desktop") | device |
| element | string $value | Screenshot only specified element | element |
| paperSize | string $value | Paper size for PDF (default is "A4") | size |
| delay | int $value | Delay in seconds between page loaded and screenshot action | delay |
| cookies | array $value | Set page cookies | cookies |
| headers | array $value | Set page headers | headers |
| geolocation | float $lat, float $lng | Set geolocation coordinates | geolocation |
| disableJS | - | Disable javascript | disablejs |
| agent | string $value | Set browser agent | agent |
| injectJS | string $value | Inject javascript into the page | injectjs |
| injectCSS | string $value | Inject CSS into the page | injectcss |
| proxy | string $value | Proxy host and port | proxy |
| getResult | - | Return raw API result | - |
| isError | - | Return TRUE if API call was unsuccessful | - |

## Note

Since the API call may take a few extra seconds to complete depending on the parameters you set (eg. `delay` or `fullPage`) it is recommended that you make calls via AJAX if adding screenshot functionality that requires user interaction via web browser.

## License

- **[BSD 2](https://opensource.org/licenses/BSD-2-Clause)**
