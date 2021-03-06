<?php
declare(strict_types=1);

namespace Lcobucci\Chimera;

/**
 * Abstraction for strategies of ID generation
 */
interface IdentifierGenerator
{
    /**
     * Generates a unique identifier
     *
     * The generated ID is supposed to be used on a resource that
     * is going to be created by a command handler
     *
     * @return mixed
     */
    public function generate();
}
