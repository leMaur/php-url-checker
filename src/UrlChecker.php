<?php

declare(strict_types=1);

namespace Lemaur\UrlChecker;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Lemaur\UrlChecker\DataTransferObject\CheckData;
use Lemaur\UrlChecker\Exceptions\BlockResponseBodyDownloadException;
use Psr\Http\Message\ResponseInterface;

final class UrlChecker
{
    /**
     * @var array<Response>
     */
    private static array $queue = [];

    /**
     * @param array<Response> $queue
     */
    public static function fake(array $queue): void
    {
        self::$queue = $queue;
    }

    public static function check(string $url, ?string $userAgent = null, ?int $connectTimeout = null, ?int $timeout = null): CheckData
    {
        try {
            $response = (new self())->getResponse($url, $userAgent, $connectTimeout, $timeout);

            if (!$response instanceof ResponseInterface) {
                return new CheckData(
                    statusCode: 500,
                    reasonPhrase: '',
                );
            }

            return new CheckData(
                statusCode: $response->getStatusCode(),
                reasonPhrase: $response->getReasonPhrase(),
                headers: $response->getHeaders(),
            );
        } catch (GuzzleException $guzzleException) {
            return new CheckData(
                statusCode: 500,
                reasonPhrase: $guzzleException->getMessage(),
            );
        }
    }

    private function isFake(): bool
    {
        return self::$queue !== [];
    }

    private function getHandler(): HandlerStack
    {
        $mockHandler = new MockHandler(self::$queue);

        return HandlerStack::create($mockHandler);
    }

    /**
     * @return array|HandlerStack[]
     */
    private function getConfig(): array
    {
        if ($this->isFake()) {
            return ['handler' => $this->getHandler()];
        }

        return [];
    }

    /**
     * @throws GuzzleException
     */
    private function getResponse(string $url, ?string $userAgent = null, ?int $connectTimeout = null, ?int $timeout = null): ?ResponseInterface
    {
        $response = null;

        $client = (new Client($this->getConfig()));

        try {
            $client->get($url, [
                'connect_timeout' => $connectTimeout ?? 2,
                'timeout' => $timeout ?? 5,
                'headers' => [
                    'User-Agent' => implode(' ', array_filter([
                        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                        'AppleWebKit/537.36 (KHTML, like Gecko)',
                        'HeadlessChrome/107.0.5304.87',
                        'Safari/537.36',
                        $userAgent,
                    ])),
                ],
                'on_headers' => static function (ResponseInterface $responseWithOnlyHeaders) use (&$response): never {
                    $response = $responseWithOnlyHeaders;
                    throw new BlockResponseBodyDownloadException();
                },
            ]);
        } catch (RequestException $requestException) {
            if (!(object) $requestException->getPrevious() instanceof BlockResponseBodyDownloadException) {
                throw $requestException;
            }
        }

        if (in_array($response?->getStatusCode(), [301, 302, 307, 308], strict: true)) {
            return $this->getResponse($response->getHeader('Location')[0] ?? '');
        }

        return $response;
    }

    public function __destruct()
    {
        self::$queue = [];
    }
}
