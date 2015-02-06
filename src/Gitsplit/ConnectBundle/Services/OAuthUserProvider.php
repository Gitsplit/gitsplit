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

namespace Gitsplit\ConnectBundle\Services;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Gitsplit\ConnectBundle\Entity\Authorization;
use Gitsplit\UserBundle\Entity\User;
use Gitsplit\UserBundle\Factory\UserFactory;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class OAuthUserProvider
 */
class OAuthUserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * @var UserProviderInterface
     *
     * Use provider
     */
    protected $userProvider;

    /**
     * @var ObjectRepository
     *
     * Use Repository
     */
    protected $authorizationRepository;

    /**
     * @var ObjectManager
     *
     * Authorization Object manager
     */
    protected $authorizationManager;

    /**
     * @var UserFactory
     *
     * User factory
     */
    private $userFactory;

    /**
     * @var ObjectManager
     *
     * User object manager
     */
    private $userObjectManager;

    /**
     * Construct
     *
     * @param UserProviderInterface $provider
     * @param ObjectRepository      $authorizationRepository
     * @param ObjectManager         $authorizationManager
     * @param UserFactory           $userFactory
     * @param ObjectManager         $userObjectManager
     */
    public function __construct(
        UserProviderInterface $provider,
        ObjectRepository $authorizationRepository,
        ObjectManager $authorizationManager,
        UserFactory $userFactory,
        ObjectManager $userObjectManager
    )
    {
        $this->userProvider = $provider;
        $this->authorizationRepository = $authorizationRepository;
        $this->authorizationManager = $authorizationManager;
        $this->userFactory = $userFactory;
        $this->userObjectManager = $userObjectManager;
    }

    /**
     * Load user by oauth user response
     *
     * @param UserResponseInterface $response Response
     *
     * @return UserInterface User
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $authorization = $this->findAuthorization($response);
        if (null === $authorization) {
            $user = $this->findOrCreateUser($response);
            $authorization = $this->createAuthorization($response, $user);
        }

        $this->updateAuthorization($authorization, $response);
        $this->save($authorization);

        return $authorization->getUser();
    }

    /**
     * Find authorization
     *
     * @param UserResponseInterface $response Response
     *
     * @return Authorization Authorization
     */
    protected function findAuthorization(UserResponseInterface $response)
    {
        $authorization = $this->authorizationRepository->findOneBy([
            'resourceOwnerName' => $response->getResourceOwner()->getName(),
            'username'          => $response->getUsername(),
        ]);

        return $authorization;
    }

    /**
     * Create a new authorization
     *
     * @param UserResponseInterface $response Response
     * @param User                  $user     User
     *
     * @return Authorization New authorization
     */
    protected function createAuthorization(UserResponseInterface $response, User $user)
    {
        $authorization = new Authorization();

        $authorization
            ->setUser($user)
            ->setResourceOwnerName($response->getResourceOwner()->getName())
            ->setUsername($response->getUsername())
            ->setClientId($response->getResourceOwner()->getOption('client_id'))
            ->setClientSecret($response->getResourceOwner()->getOption('client_secret'));

        return $authorization;
    }

    /**
     * Update existing Authorization
     *
     * @param Authorization         $authorization Authorization
     * @param UserResponseInterface $response      Response
     *
     * @return Authorization Authorization
     */
    protected function updateAuthorization(Authorization $authorization, UserResponseInterface $response)
    {
        $expirationDate = $this->getExpirationDate($response->getExpiresIn());
        $authorization
            ->setAuthorizationToken($response->getAccessToken())
            ->setExpirationDate($expirationDate);

        return $authorization;
    }

    /**
     * Save authorization
     *
     * @param Authorization $authorization Authorization
     *
     * @return $this Self object
     */
    protected function save(Authorization $authorization)
    {
        $authorization = $this->authorizationManager->merge($authorization);

        $this->authorizationManager->persist($authorization);
        $this->authorizationManager->flush($authorization);

        return $this;
    }

    /**
     * Find or create user
     *
     * @param UserResponseInterface $response Response
     *
     * @return UserInterface User
     */
    protected function findOrCreateUser(UserResponseInterface $response)
    {
        $user = $this->findUser($response);
        if (null === $user) {
            $user = $this->createUser($response);
        }

        return $user;
    }

    /**
     * Find user
     *
     * @param UserResponseInterface $response Response
     *
     * @return UserInterface User
     */
    protected function findUser(UserResponseInterface $response)
    {
        $username = $response->getEmail();

        try {
            $user = $this->userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            $user = null;
        }

        return $user;
    }

    /**
     * Create user
     *
     * @param UserResponseInterface $response Response
     *
     * @return UserInterface User
     */
    protected function createUser(UserResponseInterface $response)
    {
        /**
         * @var User $user
         */
        $user = $this->userFactory->create();
        $user
            ->setEmail($response->getEmail())
            ->setFirstname($response->getRealName())
            ->setUsername($response->getNickname());

        $this->userObjectManager->persist($user);
        $this->userObjectManager->flush($user);

        return $user;
    }

    /**
     * Return expiration date given time to expiration
     *
     * @param integer $secondsToExpiration Seconds to expiration
     *
     * @return DateTime
     */
    protected function getExpirationDate($secondsToExpiration)
    {
        return new DateTime(sprintf('now +%d seconds', $secondsToExpiration));
    }
}
