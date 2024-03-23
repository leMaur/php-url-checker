<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Lemaur\UrlChecker\UrlChecker;

it('returns status code and reason phrase', function ($statusCode, $reasonPhrase): void {
    UrlChecker::fake([new Response($statusCode)]);

    expect(UrlChecker::check('https://foo.bar'))
        ->statusCode->toBe($statusCode)
        ->reasonPhrase->toBe($reasonPhrase);
})->with([
    'status code 200' => [200, 'OK'],
    'status code 201' => [201, 'Created'],
    'status code 202' => [202, 'Accepted'],
    'status code 204' => [204, 'No Content'],
    'status code 400' => [400, 'Bad Request'],
    'status code 401' => [401, 'Unauthorized'],
    'status code 403' => [403, 'Forbidden'],
    'status code 404' => [404, 'Not Found'],
    'status code 405' => [405, 'Method Not Allowed'],
    'status code 500' => [500, 'Internal Server Error'],
    'status code 502' => [502, 'Bad Gateway'],
    'status code 503' => [503, 'Service Unavailable'],
]);
