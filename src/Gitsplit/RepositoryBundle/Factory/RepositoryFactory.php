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

namespace Gitsplit\RepositoryBundle\Factory;

use DateTime;
use Gitsplit\RepositoryBundle\Entity\Repository;

/**
 * Class RepositoryFactory
 */
class RepositoryFactory
{
    /**
     * Create new repository
     *
     * @return Repository
     */
    public function create($id)
    {
        $repository = new Repository();
        $repository
            ->setId($id)
            ->setCreatedAt(new DateTime);

        return $repository;
    }
}
 