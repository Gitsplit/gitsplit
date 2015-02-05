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

namespace Gitsplit\RepositoryBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Elcodi\Component\Core\Entity\Traits\DateTimeTrait;
use Elcodi\Component\Core\Entity\Traits\IdentifiableTrait;
use Gitsplit\UserBundle\Entity\User;

/**
 * Class Repository
 */
class Repository
{
    use IdentifiableTrait, DateTimeTrait;

    /**
     * @var string
     *
     * Url
     */
    protected $url;

    /**
     * @var string
     *
     * SSH url
     */
    protected $sshUrl;

    /**
     * @var string
     *
     * Git url
     */
    protected $gitUrl;

    /**
     * @var string
     *
     * Owner
     */
    protected $owner;

    /**
     * @var string
     *
     * Repository name
     */
    protected $name;

    /**
     * @var string
     *
     * Webhook id
     */
    protected $webhookId;

    /**
     * @var string
     *
     * Webhook secret
     */
    protected $webhookSecret;

    /**
     * @var User
     *
     * User
     */
    protected $user;

    /**
     * @var Collection
     *
     * Suites
     */
    protected $suites;

    /**
     * Get Url
     *
     * @return string Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets Url
     *
     * @param string $url Url
     *
     * @return $this Self object
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get SshUrl
     *
     * @return string SshUrl
     */
    public function getSshUrl()
    {
        return $this->sshUrl;
    }

    /**
     * Sets SshUrl
     *
     * @param string $sshUrl SshUrl
     *
     * @return $this Self object
     */
    public function setSshUrl($sshUrl)
    {
        $this->sshUrl = $sshUrl;

        return $this;
    }

    /**
     * Get GitUrl
     *
     * @return string GitUrl
     */
    public function getGitUrl()
    {
        return $this->gitUrl;
    }

    /**
     * Sets GitUrl
     *
     * @param string $gitUrl GitUrl
     *
     * @return $this Self object
     */
    public function setGitUrl($gitUrl)
    {
        $this->gitUrl = $gitUrl;

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
     * Get Name
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets Name
     *
     * @param string $name Name
     *
     * @return $this Self object
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Owner
     *
     * @return string Owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Sets Owner
     *
     * @param string $owner Owner
     *
     * @return $this Self object
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get WebhookId
     *
     * @return string WebhookId
     */
    public function getWebhookId()
    {
        return $this->webhookId;
    }

    /**
     * Sets WebhookId
     *
     * @param string $webhookId WebhookId
     *
     * @return $this Self object
     */
    public function setWebhookId($webhookId)
    {
        $this->webhookId = $webhookId;

        return $this;
    }

    /**
     * Get WebhookSecret
     *
     * @return string WebhookSecret
     */
    public function getWebhookSecret()
    {
        return $this->webhookSecret;
    }

    /**
     * Sets WebhookSecret
     *
     * @param string $webhookSecret WebhookSecret
     *
     * @return $this Self object
     */
    public function setWebhookSecret($webhookSecret)
    {
        $this->webhookSecret = $webhookSecret;

        return $this;
    }

    /**
     * Get Suites
     *
     * @return Collection Suites
     */
    public function getSuites()
    {
        return $this->suites;
    }

    /**
     * Sets Suites
     *
     * @param Collection $suites Suites
     *
     * @return $this Self object
     */
    public function setSuites($suites)
    {
        $this->suites = $suites;

        return $this;
    }
}

 