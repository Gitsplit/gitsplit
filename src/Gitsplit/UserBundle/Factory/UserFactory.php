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

namespace Gitsplit\UserBundle\Factory;

use DateTime;
use Gitsplit\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * Class UserFactory
 */
class UserFactory
{
    /**
     * Create
     */
    public function create()
    {
        $user = new User();

        $generator = new SecureRandom();
        $user
            ->setSalt($generator->nextBytes(100))
            ->setCreatedAt(new DateTime());

        return $user;
    }
}
