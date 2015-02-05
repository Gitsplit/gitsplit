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

namespace Gitsplit\RepositoryBundle\DependencyInjection;

use Elcodi\Bundle\CoreBundle\DependencyInjection\Abstracts\AbstractExtension;

/**
 * Class GitsplitRepositoryExtension
 */
class GitsplitRepositoryExtension extends AbstractExtension
{
    /**
     * @var string
     *
     * Extension name
     */
    const EXTENSION_NAME = 'gitsplit_repository';

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
            'gitsplit.entity.repository.class' => $config['mapping']['repository']['class'],
            'gitsplit.entity.Repository.mapping_file' => $config['mapping']['repository']['mapping_file'],
            'gitsplit.entity.Repository.manager' => $config['mapping']['repository']['manager'],
            'gitsplit.entity.Repository.enabled' => $config['mapping']['repository']['enabled'],
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
            'services',
            'eventListeners',
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
