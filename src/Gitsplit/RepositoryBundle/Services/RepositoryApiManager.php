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

namespace Gitsplit\RepositoryBundle\Services;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Gitsplit\RepositoryBundle\Entity\Repository;
use Gitsplit\RepositoryBundle\Traits\AuthenticatedClientTrait;
use Gitsplit\UserBundle\Entity\User;

/**
 * Class RepositoryApiManager
 */
class RepositoryApiManager
{
    use AuthenticatedClientTrait;

    /**
     * @var ObjectManager
     *
     * User Object manager
     */
    protected $userObjectManager;

    /**
     * Construct
     *
     * @param ObjectManager $userObjectManager Repository object manager
     */
    public function __construct(ObjectManager $userObjectManager)
    {
        $this->userObjectManager = $userObjectManager;
    }

    /**
     * Get repository formatted array grouped by organizations
     *
     * @param User $user User
     *
     * @return array Repositories
     */
    public function load(User $user)
    {
        $repositoriesPlain = json_decode($user->getRepositoriesPlain(), true);

        if (null === $repositoriesPlain) {

            /**
             * @var Collection $enabledRepositoriesIds
             */
            $enabledRepositoriesIds = $user
                ->getRepositories()
                ->map(function (Repository $repository) {
                    return $repository->getId();
                });

            $repositoriesPlain = [];
            $repositoryStack = $this->loadAllRepositoriesFromGithubApi($user);

            foreach ($repositoryStack as $organization => $remoteRepositories) {

                $repositoriesPlain[$organization] = [];

                foreach ($remoteRepositories as $remoteRepository) {

                    $remoteRepositoryId = $remoteRepository['id'];
                    $repositoriesPlain[$organization][$remoteRepositoryId] = [
                        'id'      => $remoteRepository['id'],
                        'url'     => $remoteRepository['html_url'],
                        'name'    => $remoteRepository['name'],
                        'owner'   => $remoteRepository['owner']['login'],
                        'enabled' => $enabledRepositoriesIds->contains($remoteRepositoryId),
                    ];
                }
            }

            $user->setRepositoriesPlain(json_encode($repositoriesPlain));
            $this
                ->userObjectManager
                ->flush($user);
        }

        return $repositoriesPlain;
    }

    /**
     * Get all user repositories from Github api, grouped by organization name
     *
     * @param User $user User
     *
     * @return array Repositories grouped by organization name
     */
    protected function loadAllRepositoriesFromGithubApi(User $user)
    {
        $client = $this->getAuthenticatedClient($user);
        $organizations = $client
            ->api('current_user')
            ->setPerPage(1000)
            ->organizations('all');

        $repositories = [];

        foreach ($organizations as $organization) {

            $repositories[$organization['login']] = $client
                ->api('organization')
                ->setPerPage(1000)
                ->repositories($organization['login']);
        }

        $repositories[$user->getUsername()] = $client
            ->api('current_user')
            ->setPerPage(1000)
            ->repositories('all');

        return $repositories;
    }
}
