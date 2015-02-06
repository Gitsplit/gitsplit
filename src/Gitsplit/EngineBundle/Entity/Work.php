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
use Elcodi\Component\Core\Entity\Traits\DateTimeTrait;
use Elcodi\Component\Core\Entity\Traits\IdentifiableTrait;

/**
 * Class Work
 */
class Work
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
     * @var string
     *
     * Path
     */
    protected $path;

    /**
     * @var string
     *
     * remote
     */
    protected $remote;

    /**
     * @var Suite
     *
     * Suite
     */
    protected $suite;

    /**
     * @var string
     *
     * Log
     */
    protected $log;

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
     * @param $suite
     * @param $path
     * @param $remote
     */
    public function __construct($suite, $path, $remote)
    {
        $this->suite = $suite;
        $this->path = $path;
        $this->remote = $remote;
        $this->status = self::STATUS_EMPTY;
        $this->log = '';
        $this->createdAt = new DateTime();
    }

    /**
     * Get Path
     *
     * @return string Path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get Remote
     *
     * @return string Remote
     */
    public function getRemote()
    {
        return $this->remote;
    }

    /**
     * Get Suite
     *
     * @return Suite Suite
     */
    public function getSuite()
    {
        return $this->suite;
    }

    /**
     * Get Log
     *
     * @return string Log
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Sets Log
     *
     * @param string $log Log
     *
     * @return $this Self object
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Append Log
     *
     * @param string $log Log
     *
     * @return $this Self object
     */
    public function appendLog($log)
    {
        $this->log .= $log;

        return $this;
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
