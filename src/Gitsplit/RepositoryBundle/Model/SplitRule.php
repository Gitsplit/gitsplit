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

namespace Gitsplit\RepositoryBundle\Model;

/**
 * Class SplitRule
 */
class SplitRule
{
    /**
     * @var string
     *
     * Path
     */
    protected $path;

    /**
     * @var string
     *
     * Destination Repository
     */
    protected $destinationRepository;

    /**
     * Construct
     *
     * @param string $path                  Path
     * @param string $destinationRepository Destination Repository
     */
    public function __construct($path, $destinationRepository)
    {
        $this->path = $path;
        $this->destinationRepository = $destinationRepository;
    }

    /**
     * Get DestinationRepository
     *
     * @return string DestinationRepository
     */
    public function getDestinationRepository()
    {
        return $this->destinationRepository;
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
}
