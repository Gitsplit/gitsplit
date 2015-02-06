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
 * Class OrganizationController
 *
 * @Route(
 *      path = "/organization"
 * )
 */
class OrganizationController extends Controller
{
    /**
     * Organization name
     *
     * @Route(
     *      path = "/{organizationName}",
     *      name = "gitsplit_organization_view",
     *      methods = {"GET"}
     * )
     */
    public function viewOrganization($organizationName)
    {
        $repositories = $this
            ->get('gitsplit.repository_api_manager')
            ->load($this->getUser())[$organizationName];

        return $this->render(
            ":Organization:view.html.twig",
            [
                'organization' => $organizationName,
                'repositories' => $repositories,

            ]
        );
    }
}
