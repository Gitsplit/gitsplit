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

namespace Gitsplit\EngineBundle\Factory;

use Gitsplit\EngineBundle\Entity\Suite;
use Gitsplit\RepositoryBundle\Entity\Repository;
use Gitsplit\UserBundle\Entity\User;

/**
 * Class SuiteFactory
 */
class SuiteFactory
{
    /**
     * Create new suite
     *
     * @param User       $user
     * @param Repository $repository
     *
     * @return Suite
     */
    public function create(
        User $user,
        Repository $repository
    )
    {
        return new Suite(
            $user,
            $repository
        );
    }
}
