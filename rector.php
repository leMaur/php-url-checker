<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
    ])
    ->withPhpSets(
        php81: true,
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
    )
    ->withImportNames(
        removeUnusedImports: true,
    )
    ->withRules([
        AddPropertyTypeDeclarationRector::class,
    ]);
