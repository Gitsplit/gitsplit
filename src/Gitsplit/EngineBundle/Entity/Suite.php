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

namespace Gitsplit\EngineBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Elcodi\Component\Core\Entity\Traits\DateTimeTrait;
use Elcodi\Component\Core\Entity\Traits\IdentifiableTrait;
use Gitsplit\RepositoryBundle\Entity\Repository;
use Gitsplit\UserBundle\Entity\User;

/**
 * Class Suite
 */
class Suite
{
    use IdentifiableTrait, DateTimeTrait;

    /**
     * @var integer
     *
     * Status empty
     */
    const STATUS_EMPTY = 0;

    /**
     * @var integer
     *
     * Status active
     */
    const STATUS_ACTIVE = 1;

    /**
     * @var integer
     *
     * Status finished
     */
    const STATUS_FINISHED = 2;

    /**
     * @var User
     *
     * User
     */
    protected $user;

    /**
     * @var Repository
     *
     * Repository
     */
    protected $repository;

    /**
     * @var Collection
     *
     * Works
     */
    protected $works;

    /**
     * @var integer
     *
     * Status
     */
    protected $status;

    /**
     * @var integer
     *
     * Result
     */
    protected $result;

    /**
     * @param $user
     * @param $repository
     */
    function __construct($user, $repository)
    {
        $this->user = $user;
        $this->repository = $repository;
        $this->works = new ArrayCollection();
        $this->status = self::STATUS_EMPTY;
        $this->createdAt = new DateTime();
    }


    /**
     * Get Repository
     *
     * @return Repository Repository
     */
    public function getRepository()
    {
        return $this->repository;
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
     * Get Works
     *
     * @return mixed Works
     */
    public function getWorks()
    {
        return $this->works;
    }

    /**
     * Get Result
     *
     * @return int Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets Result
     *
     * @param int $result Result
     *
     * @return $this Self object
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get Status
     *
     * @return int Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets Status
     *
     * @param int $status Status
     *
     * @return $this Self object
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
 