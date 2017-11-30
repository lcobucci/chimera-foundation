<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Tests\DependencyInjection;

use Lcobucci\Chimera\Bus\Tactician\DependencyInjection\RegisterServices as BusService;
use Lcobucci\Chimera\Routing\Expressive\DependencyInjection\RegisterServices as RoutingService;
use Lcobucci\Chimera\DependencyInjection\Package;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

final class PackageTest extends TestCase
{
    /**
     * @test
     */
    public function getCompilerPassesShouldReturnQueryAndCommandBusServices(): void
    {
        $appName          = 'application';
        $busDependency    = 'bus-dependency-a';
        $routerDependency = 'router-dependency-a';

        $passes = iterator_to_array(
            (new Package($appName, [$busDependency], [$routerDependency]))->getCompilerPasses()
        );

        self::assertEquals(
            new BusService($appName . '.command_bus', $appName . '.query_bus', [$busDependency]),
            $passes[0][0]
        );
        self::assertEquals(
            new RoutingService($appName, $appName . '.command_bus', $appName . '.query_bus', [$routerDependency]),
            $passes[1][0]
        );

        self::assertSame(PassConfig::TYPE_BEFORE_OPTIMIZATION, $passes[0][1]);
        self::assertSame(PassConfig::TYPE_BEFORE_OPTIMIZATION, $passes[1][1]);
    }
}
