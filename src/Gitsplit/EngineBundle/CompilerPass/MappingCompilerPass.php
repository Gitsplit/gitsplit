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

namespace Gitsplit\EngineBundle\CompilerPass;

use Mmoreram\SimpleDoctrineMapping\CompilerPass\Abstracts\AbstractMappingCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class MappingCompilerPass
 */
class MappingCompilerPass extends AbstractMappingCompilerPass
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $this
            ->addEntityMapping(
                $container,
                'gitsplit.entity.suite.manager',
                'gitsplit.entity.suite.class',
                'gitsplit.entity.suite.mapping_file',
                'gitsplit.entity.suite.enabled'
            )
            ->addEntityMapping(
                $container,
                'gitsplit.entity.work.manager',
                'gitsplit.entity.work.class',
                'gitsplit.entity.work.mapping_file',
                'gitsplit.entity.work.enabled'
            )
        ;
    }
}
