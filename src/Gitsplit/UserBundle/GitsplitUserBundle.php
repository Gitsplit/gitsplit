<?php

/*
 * This file is part of the GitSplit package.
 *
 * Copyright (c) 2015 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Gitsplit\UserBundle;

use Gitsplit\UserBundle\CompilerPass\MappingCompilerPass;
use Gitsplit\UserBundle\DependencyInjection\GitsplitUserExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class GitsplitUserBundle
 */
class GitsplitUserBundle extends Bundle
{
    /**
     * Returns the bundle's container extension.
     *
     * @return GitsplitUserExtension The container extension
     */
    public function getContainerExtension()
    {
        return new GitsplitUserExtension();
    }

    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MappingCompilerPass());
    }
}
