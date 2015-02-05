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

use Elcodi\Component\Core\Entity\Traits\IdentifiableTrait;
use Gitsplit\UserBundle\Entity\User;

/**
 * Class Authorization
 */
class Authorization
{
    use IdentifiableTrait;

    /**
     * Name of the resource owner
     *
     * @var string
     */
    protected $resourceOwnerName;

    /**
     * Username in the remote system
     *
     * @var string
     */
    protected $username;

    /**
     * Authorization token, when it suits
     *
     * @var string
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
     * Expiration date
     *
     * @var \DateTime
     */
    protected $expirationDate;

    /**
     * User
     *
     * @var User
     */
    protected $user;

    /**
     * @return string
     */
    public function getAuthorizationToken()
    {
        return $this->authorizationToken;
    }

    /**
     * @param string $authorizationToken
     *
     * @return self
     */
    public function setAuthorizationToken($authorizationToken)
    {
        $this->authorizationToken = $authorizationToken;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $expirationDate
     *
     * @return self
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceOwnerName()
    {
        return $this->resourceOwnerName;
    }

    /**
     * @param string $resourceOwnerName
     *
     * @return self
     */
    public function setResourceOwnerName($resourceOwnerName)
    {
        $this->resourceOwnerName = $resourceOwnerName;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

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
}
