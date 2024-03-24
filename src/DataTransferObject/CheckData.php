<?php

declare(strict_types=1);

namespace Lemaur\UrlChecker\DataTransferObject;

final class CheckData
{
    public function __construct(
        public int $statusCode,
        public string $reasonPhrase,
        /**
         * @var string[][]
         */
        public array $headers = [],
    ) {
    }
}
