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

use Elcodi\Bundle\CoreBundle\DependencyInjection\Abstracts\AbstractExtension;

/**
 * Class GitsplitEngineExtension
 */
class GitsplitEngineExtension extends AbstractExtension
{
    /**
     * @var string
     *
     * Extension name
     */
    const EXTENSION_NAME = 'gitsplit_engine';

    /**
     * @return string
     */
    public function getConfigFilesLocation()
    {
        return __DIR__ . '/../Resources/config';
    }

    /**
     * @return Configuration
     */
    protected function getConfigurationInstance()
    {
        return new Configuration(static::EXTENSION_NAME);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function getParametrizationValues(array $config)
    {
        return [
            'gitsplit.entity.suite.class' => $config['mapping']['suite']['class'],
            'gitsplit.entity.suite.mapping_file' => $config['mapping']['suite']['mapping_file'],
            'gitsplit.entity.suite.manager' => $config['mapping']['suite']['manager'],
            'gitsplit.entity.suite.enabled' => $config['mapping']['suite']['enabled'],

            'gitsplit.entity.work.class' => $config['mapping']['work']['class'],
            'gitsplit.entity.work.mapping_file' => $config['mapping']['work']['mapping_file'],
            'gitsplit.entity.work.manager' => $config['mapping']['work']['manager'],
            'gitsplit.entity.work.enabled' => $config['mapping']['work']['enabled'],
        ];
    }

    /**
     * @param array $config
     *
     * @return array
     */
    public function getConfigFiles(array $config)
    {
        return [
            'factories',
            'objectManagers',
            'repositories',
        ];
    }

    /**
     * Returns the extension alias, same value as extension name
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return self::EXTENSION_NAME;
    }
}
