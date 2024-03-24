# Check the URL HTTP response status code and reason phrase.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lemaur/php-url-checker.svg?style=flat-square)](https://packagist.org/packages/lemaur/php-url-checker)
[![Total Downloads](https://img.shields.io/packagist/dt/lemaur/php-url-checker.svg?style=flat-square)](https://packagist.org/packages/lemaur/php-url-checker)
[![License](https://img.shields.io/packagist/l/lemaur/php-url-checker.svg?style=flat-square&color=yellow)](https://github.com/leMaur/php-url-checker/blob/main/LICENSE.md)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/lemaur/php-url-checker/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/leMaur/php-url-checker/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/lemaur/php-url-checker/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/leMaur/php-url-checker/actions/workflows/fix-php-code-style-issues.yml)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/lemaur?style=flat-square&color=ea4aaa)](https://github.com/sponsors/leMaur)
[![Trees](https://img.shields.io/badge/dynamic/json?color=yellowgreen&style=flat-square&label=Trees&query=%24.total&url=https%3A%2F%2Fpublic.offset.earth%2Fusers%2Flemaur%2Ftrees)](https://ecologi.com/lemaur?r=6012e849de97da001ddfd6c9)

This package for PHP provide a class to check the URL HTTP response without downloading the HTTP content in response.

This reduces the bandwidth and the response time.

## Support Me

Hey folks,

Do you like this package? Do you find it useful and it fits well in your project?

I am glad to help you, and I would be so grateful if you considered supporting my work.

You can even choose 😃:
* You can [sponsor me 😎](https://github.com/sponsors/leMaur) with a monthly subscription.
* You can [buy me a coffee ☕ or a pizza 🍕](https://github.com/sponsors/leMaur?frequency=one-time&sponsor=leMaur) just for this package.
* You can [plant trees 🌴](https://ecologi.com/lemaur?r=6012e849de97da001ddfd6c9). By using this link we will both receive 30 trees for free and the planet (and me) will thank you. 
* You can "Star ⭐" this repository (it's free 😉).

## Installation

You can install the package via composer:

```bash
composer require lemaur/php-url-checker
```

## Usage

The class `Lemaur\UrlChecker\UrlChecker` provides a static method `check` where accepts the URL to check as first parameter
and the user agent string as a second parameter.  

Here you can see how to use it 👇

```php
use Lemaur\UrlChecker\UrlChecker;

$response = UrlChecker::check(
    url: 'https://google.com', 
    userAgent: 'MyApp/1.0 (UrlChecker)',
    connectTimeout: 5,
    timeout: 10,
);
// \Lemaur\UrlChecker\DataTransferObject\CheckData

$response->statusCode;
// (int) 200

$response->reasonPhrase;
// (string) 'OK'

$response->headers:
// (array) ['Date' => ['Sun, 24 Mar 2024 09:06:08 GMT']]
```

That class also provides another method to help you write unit tests.

It mocks the response to prevents any external network call. It accepts an array of `GuzzleHttp\Psr7\Response`.
```php
UrlChecker::fake([
    new \GuzzleHttp\Psr7\Response(200),
]);

$response = UrlChecker::check('https://dummy-url.com');

$response->statusCode;
// (int) 200
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security Vulnerability

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

This package has been inspired by [Chris White](https://github.com/cwhite92)'s [blog post](https://chriswhite.is/coding/inspecting-http-response-headers-without-downloading-body-with-guzzle/). 

- [Maurizio](https://github.com/lemaur)
- [Chris White](https://github.com/cwhite92)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
