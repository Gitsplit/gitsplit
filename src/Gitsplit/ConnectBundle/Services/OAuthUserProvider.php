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
 *
 * @author Berny Cantos <be@rny.cc>
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
     * @param UserResponseInterface $response
     *
     * @return UserInterface
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
     * @param UserResponseInterface $response
     *
     * @return Authorization
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
     * @param UserResponseInterface $response
     * @param User                  $user
     *
     * @return Authorization
     */
    protected function createAuthorization(UserResponseInterface $response, User $user)
    {
        $authorization = new Authorization();

        $authorization
            ->setUser($user)
            ->setResourceOwnerName($response->getResourceOwner()->getName())
            ->setUsername($response->getUsername())
            ->setClientId($response->getResourceOwner()->getOption('client_id'))
            ->setClientSecret($response->getResourceOwner()->getOption('client_secret'))
        ;

        return $authorization;
    }

    /**
     * @param Authorization         $authorization
     * @param UserResponseInterface $response
     *
     * @return Authorization
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
     * @param Authorization $authorization
     */
    protected function save(Authorization $authorization)
    {
        $authorization = $this->authorizationManager->merge($authorization);

        $this->authorizationManager->persist($authorization);
        $this->authorizationManager->flush($authorization);
    }

    /**
     * @param UserResponseInterface $response
     *
     * @return UserInterface
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
     * @param UserResponseInterface $response
     *
     * @return UserInterface
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
     * @param UserResponseInterface $response
     *
     * @return UserInterface
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
     * @param integer $secondsToExpiration
     *
     * @return \DateTime
     */
    protected function getExpirationDate($secondsToExpiration)
    {
        return new DateTime(sprintf('now +%d seconds', $secondsToExpiration));
    }
}
