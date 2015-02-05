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

namespace Gitsplit\EngineBundle\DependencyInjection;

use Elcodi\Bundle\CoreBundle\DependencyInjection\Abstracts\AbstractConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Berny Cantos <be@rny.cc>
 */
class Configuration extends AbstractConfiguration implements ConfigurationInterface
{
    /**
     * Configure the root node
     *
     * @param ArrayNodeDefinition $rootNode
     */
    protected function setupTree(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('mapping')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->addMappingNode(
                            'suite',
                            'Gitsplit\EngineBundle\Entity\Suite',
                            '@GitsplitEngineBundle/Resources/config/doctrine/Suite.orm.yml',
                            'default',
                            true
                        ))
                        ->append($this->addMappingNode(
                            'work',
                            'Gitsplit\EngineBundle\Entity\Work',
                            '@GitsplitEngineBundle/Resources/config/doctrine/Work.orm.yml',
                            'default',
                            true
                        ))
                    ->end()
                ->end()
            ->end()
        ;
    }
}
