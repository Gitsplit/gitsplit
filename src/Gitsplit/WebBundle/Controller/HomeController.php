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

namespace Gitsplit\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomeController
 *
 * @Route(
 *      path = "/"
 * )
 */
class HomeController extends Controller
{
    /**
     * Nav
     *
     * @Route(
     *      path = "nav",
     *      name = "gitsplit_nav",
     *      methods = {"GET"}
     * )
     */
    public function navAction()
    {
        $repositories = $this
            ->get('gitsplit.repository_api_manager')
            ->load($this->getUser());

        return $this->render(
            "::nav.html.twig",
            [
                'repositories' => $repositories,

            ]
        );
    }

    /**
     * Home
     *
     * @Route(
     *      path = "",
     *      name = "gitsplit_home",
     *      methods = {"GET"}
     * )
     */
    public function viewAction()
    {
        $repositories = $this
            ->get('gitsplit.repository_api_manager')
            ->load($this->getUser());

        return $this->render(
            ":Home:view.html.twig",
            [
                'repositories' => $repositories,

            ]
        );
    }
}
