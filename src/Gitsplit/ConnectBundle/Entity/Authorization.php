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

namespace Gitsplit\ConnectBundle\Entity;

use DateTime;
use Elcodi\Component\Core\Entity\Traits\IdentifiableTrait;
use Gitsplit\UserBundle\Entity\User;

/**
 * Class Authorization
 */
class Authorization
{
    use IdentifiableTrait;

    /**
     * @var string
     *
     * Name of the resource owner
     */
    protected $resourceOwnerName;

    /**
     * @var string
     *
     * Username in the remote system
     */
    protected $username;

    /**
     * @var string
     *
     * Authorization token, when it suits
     */
    protected $authorizationToken;

    /**
     * @var string
     *
     * Client id
     */
    protected $clientId;

    /**
     * @var string
     *
     * Client secret
     */
    protected $clientSecret;

    /**
     * @var DateTime
     *
     * Expiration date
     */
    protected $expirationDate;

    /**
     * @var User
     *
     * User
     */
    protected $user;

    /**
     * Get AuthorizationToken
     *
     * @return string AuthorizationToken
     */
    public function getAuthorizationToken()
    {
        return $this->authorizationToken;
    }

    /**
     * Sets AuthorizationToken
     *
     * @param string $authorizationToken AuthorizationToken
     *
     * @return $this Self object
     */
    public function setAuthorizationToken($authorizationToken)
    {
        $this->authorizationToken = $authorizationToken;

        return $this;
    }

    /**
     * Get ClientId
     *
     * @return string ClientId
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Sets ClientId
     *
     * @param string $clientId ClientId
     *
     * @return $this Self object
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get ClientSecret
     *
     * @return string ClientSecret
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Sets ClientSecret
     *
     * @param string $clientSecret ClientSecret
     *
     * @return $this Self object
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get ExpirationDate
     *
     * @return \DateTime ExpirationDate
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Sets ExpirationDate
     *
     * @param \DateTime $expirationDate ExpirationDate
     *
     * @return $this Self object
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get ResourceOwnerName
     *
     * @return string ResourceOwnerName
     */
    public function getResourceOwnerName()
    {
        return $this->resourceOwnerName;
    }

    /**
     * Sets ResourceOwnerName
     *
     * @param string $resourceOwnerName ResourceOwnerName
     *
     * @return $this Self object
     */
    public function setResourceOwnerName($resourceOwnerName)
    {
        $this->resourceOwnerName = $resourceOwnerName;

        return $this;
    }

    /**
     * Get User
     *
     * @return User User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets User
     *
     * @param User $user User
     *
     * @return $this Self object
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get Username
     *
     * @return string Username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets Username
     *
     * @param string $username Username
     *
     * @return $this Self object
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
}
