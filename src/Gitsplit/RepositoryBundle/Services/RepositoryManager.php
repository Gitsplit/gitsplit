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

use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Gitsplit\RepositoryBundle\Entity\Repository;
use Gitsplit\RepositoryBundle\Factory\RepositoryFactory;
use Gitsplit\RepositoryBundle\Repository\RepositoryRepository;
use Gitsplit\UserBundle\Entity\User;

/**
 * Class RepositoryManager
 */
class RepositoryManager
{
    /**
     * @var RepositoryFactory
     *
     * Repository factory
     */
    protected $repositoryFactory;

    /**
     * @var RepositoryRepository
     *
     * Repository repository
     */
    protected $repositoryRepository;

    /**
     * @var ObjectManager
     *
     * Repository Object manager
     */
    protected $repositoryObjectManager;

    /**
     * Construct
     *
     * @param $repositoryFactory       Repository factory
     * @param $repositoryRepository    Repository repository
     * @param $repositoryObjectManager Repository object manager
     */
    public function __construct($repositoryFactory, $repositoryRepository, $repositoryObjectManager)
    {
        $this->repositoryFactory = $repositoryFactory;
        $this->repositoryRepository = $repositoryRepository;
        $this->repositoryObjectManager = $repositoryObjectManager;
    }

    /**
     * Get all user repositories
     *
     * @param User $user User
     *
     * @return array Repositories
     */
    public function loadAllRepositories(User $user)
    {
        $repositories = $this
            ->getAuthenticatedClient($user)
            ->api('current_user')
            ->repositories('all');

        return $repositories;
    }

    /**
     * Add a new repository if does not exist.
     * If exists, return existing one.
     *
     * @param User    $user         User
     * @param integer $repositoryId Repository id
     *
     * @return $this Self object
     *
     * @throws Exception The repository is not found
     */
    public function addRepository(User $user, $repositoryId)
    {
        $existentRepository = $this
            ->repositoryRepository
            ->findBy([
                'user' => $user,
                'id'   => $repositoryId
            ]);

        if ($existentRepository instanceof Repository) {
            return $existentRepository;
        }

        $specificRepository = array_filter(
            $this->loadAllRepositories($user),
            function (array $repository) use ($repositoryId) {
                return ($repository['id'] === $repositoryId);
            });

        /**
         * Repository not found
         */
        if (empty($specificRepository)) {

            throw new Exception('Repository not found');
        }

        $specificRepository = reset($specificRepository);
        $repository = $this
            ->repositoryFactory
            ->create($specificRepository['id'])
            ->setUrl($specificRepository['html_url'])
            ->setSshUrl($specificRepository['ssh_url'])
            ->setGitUrl($specificRepository['git_url'])
            ->setOwner($specificRepository['owner']['login'])
            ->setName($specificRepository['name'])
            ->setUser($user);

        $this->repositoryObjectManager->persist($repository);
        $this->repositoryObjectManager->flush($repository);

        return $repository;
    }

    /**
     * Remove repository
     */
    public function removeRepository(Repository $repository)
    {
        $this->repositoryObjectManager->remove($repository);
        $this->repositoryObjectManager->flush($repository);

        return $this;
    }

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
