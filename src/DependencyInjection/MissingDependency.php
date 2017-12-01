<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\DependencyInjection;

use Lcobucci\Chimera\Exception;

final class MissingDependency extends \RuntimeException implements Exception
{
    private const ERROR_MESSAGE = 'Application cannot be registered because no implementation for "%s" was found.';

    public static function serviceBusMissing(string $class): self
    {
        return new self(sprintf(self::ERROR_MESSAGE, $class));
    }

    public static function routingMissing(string $class): self
    {
        return new self(sprintf(self::ERROR_MESSAGE, $class));
    }
}
