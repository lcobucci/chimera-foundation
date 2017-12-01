<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\DependencyInjection;

use Lcobucci\Chimera\Bus\Tactician\DependencyInjection as Tactician;
use Lcobucci\Chimera\Routing\Expressive\DependencyInjection as Expressive;
use Lcobucci\DependencyInjection\CompilerPassListProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

final class Package implements CompilerPassListProvider
{
    /**
     * @var string
     */
    private $appName;

    /**
     * @var array
     */
    private $busDependencies;

    /**
     * @var array
     */
    private $routingDependencies;

    public function __construct(string $appName, array $busDependencies = [], array $routingDependencies = [])
    {
        $this->appName             = $appName;
        $this->busDependencies     = $busDependencies;
        $this->routingDependencies = $routingDependencies;
    }

    public function getCompilerPasses(): \Generator
    {
        $commandBus = $this->appName . '.command_bus';
        $queryBus   = $this->appName . '.query_bus';

        yield [$this->createBusCompilerPass($commandBus, $queryBus), PassConfig::TYPE_BEFORE_OPTIMIZATION];
        yield [$this->createRoutingCompilerPass($commandBus, $queryBus), PassConfig::TYPE_BEFORE_OPTIMIZATION];
    }

    private function createBusCompilerPass(string $commandBus, string $queryBus): CompilerPassInterface
    {
        if (class_exists(Tactician\RegisterServices::class)) {
            return new Tactician\RegisterServices($commandBus, $queryBus, $this->busDependencies);
        }

        throw MissingDependency::serviceBusMissing(Tactician\RegisterServices::class);
    }

    private function createRoutingCompilerPass(string $commandBus, string $queryBus): CompilerPassInterface
    {
        if (class_exists(Expressive\RegisterServices::class)) {
            return new Expressive\RegisterServices($this->appName, $commandBus, $queryBus, $this->routingDependencies);
        }

        throw MissingDependency::routingMissing(Expressive\RegisterServices::class);
    }
}
