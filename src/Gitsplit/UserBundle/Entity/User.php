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

namespace Gitsplit\UserBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Elcodi\Component\Core\Entity\Traits\DateTimeTrait;
use Elcodi\Component\Core\Entity\Traits\IdentifiableTrait;
use Gitsplit\ConnectBundle\Entity\Authorization;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 */
class User implements UserInterface
{
    use IdentifiableTrait, DateTimeTrait;

    /**
     * @var string
     *
     * Email
     */
    protected $email;

    /**
     * @var string
     *
     * First name
     */
    protected $firstName;

    /**
     * @var string
     *
     * Last name
     */
    protected $username;

    /**
     * @var string
     *
     * Salt
     */
    protected $salt;

    /**
     * @var Collection
     *
     * Repositories
     */
    protected $repositories;

    /**
     * @var string
     *
     * Repositories plain
     */
    protected $repositoriesPlain;

    /**
     * @var Authorization
     *
     * Authorization
     */
    protected $authorization;

    /**
     * Get Email
     *
     * @return string Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets Email
     *
     * @param string $email Email
     *
     * @return $this Self object
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get FirstName
     *
     * @return string FirstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets FirstName
     *
     * @param string $firstName FirstName
     *
     * @return $this Self object
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

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

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Sets Salt
     *
     * @param string $salt Salt
     *
     * @return $this Self object
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get Repositories
     *
     * @return Collection Repositories
     */
    public function getRepositories()
    {
        return $this->repositories;
    }

    /**
     * Get RepositoriesPlain
     *
     * @return string RepositoriesPlain
     */
    public function getRepositoriesPlain()
    {
        return $this->repositoriesPlain;
    }

    /**
     * Sets RepositoriesPlain
     *
     * @param string $repositoriesPlain RepositoriesPlain
     *
     * @return $this Self object
     */
    public function setRepositoriesPlain($repositoriesPlain)
    {
        $this->repositoriesPlain = $repositoriesPlain;

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Get authentication
     *
     * @return Authorization Authorization
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }
}
 