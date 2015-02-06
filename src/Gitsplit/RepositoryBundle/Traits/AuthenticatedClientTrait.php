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

namespace Gitsplit\RepositoryBundle\Traits;

use Gitsplit\UserBundle\Entity\User;

/**
 * Trait AuthenticatedClientTrait
 */
trait AuthenticatedClientTrait
{
    /**
     * Return an authenticated client instance given a user
     *
     * @param User $user User
     *
     * @return \Github\Client Authenticated client
     */
    public function getAuthenticatedClient(User $user)
    {
        $authorization = $user->getAuthorization();
        $client = new \Github\Client();

        $client
            ->authenticate(
                $authorization->getAuthorizationToken(),
                null,
                \Github\Client::AUTH_HTTP_TOKEN
            );

        return $client;
    }
}
