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
use Gitsplit\RepositoryBundle\Traits\AuthenticatedClientTrait;
use Gitsplit\UserBundle\Entity\User;

/**
 * Class RepositoryManager
 */
class RepositoryManager
{
    use AuthenticatedClientTrait;

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
     * @var RepositoryApiManager
     *
     * Repository API manager
     */
    protected $repositoryApiManager;

    /**
     * Construct
     *
     * @param RepositoryFactory    $repositoryFactory       Repository factory
     * @param RepositoryRepository $repositoryRepository    Repository repository
     * @param ObjectManager        $repositoryObjectManager Repository object manager
     * @param RepositoryApiManager $repositoryApiManager    Repository object manager
     */
    public function __construct(
        RepositoryFactory $repositoryFactory,
        RepositoryRepository $repositoryRepository,
        ObjectManager $repositoryObjectManager,
        RepositoryApiManager $repositoryApiManager
    )
    {
        $this->repositoryFactory = $repositoryFactory;
        $this->repositoryRepository = $repositoryRepository;
        $this->repositoryObjectManager = $repositoryObjectManager;
        $this->repositoryApiManager = $repositoryApiManager;
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
            ->findOneBy([
                'user' => $user,
                'id'   => $repositoryId
            ]);

        if ($existentRepository instanceof Repository) {
            return $existentRepository;
        }

        $specificRepository = null;
        $allOrganizations = $this
            ->repositoryApiManager
            ->loadAllRepositoriesFromGithubApi($user);

        foreach ($allOrganizations as $organization) {

            if (is_array($organization)) {

                foreach ($organization as $repository) {

                    if ($repository['id'] === $repositoryId) {

                        $specificRepository = $repository;
                        break 2;
                    }
                }
            }
        }

        /**
         * Repository not found
         */
        if (empty($specificRepository)) {

            throw new Exception('Repository not found');
        }

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
}
