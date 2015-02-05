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

use Gitsplit\RepositoryBundle\Entity\Repository;
use Mmoreram\ControllerExtraBundle\Annotation\Entity as EntityAnnotation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class RepositoryController
 */
class RepositoryController extends Controller
{
    /**
     * Reload repositories
     *
     * @Route(
     *      path = "/repositories/reload",
     *      name = "gitsplit_repositories_reload",
     *      methods = {"GET"}
     * )
     */
    public function reloadAction()
    {
        $user = $this->getUser();
        $user->setRepositoriesPlain(null);
        $this
            ->get('gitsplit.object_manager.user')
            ->flush($user);

        return $this->redirectToRoute('gitsplit_home');
    }

    /**
     * Load repository
     *
     * @Route(
     *      path = "/repository/add/{id}",
     *      name = "gitsplit_repository_add",
     *      requirements = {
     *          "id" = "\d+",
     *      },
     *      methods = {"GET"}
     * )
     */
    public function addAction($id)
    {
        $user = $this->getUser();
        $this
            ->get('gitsplit.repository_manager')
            ->addRepository(
                $this->getUser(),
                $id
            );

        $repositoriesPlain = json_decode($user->getRepositoriesPlain(), true);
        if ($repositoriesPlain[$id]) {

            $repositoriesPlain[$id]['enabled'] = true;
        }
        $user->setRepositoriesPlain(json_encode($repositoriesPlain));
        $this
            ->get('gitsplit.object_manager.user')
            ->flush($user);

        return $this->redirectToRoute('gitsplit_home');
    }

    /**
     * Load repository
     *
     * @Route(
     *      path = "/repository/remove/{id}",
     *      name = "gitsplit_repository_remove",
     *      requirements = {
     *          "id" = "\d+",
     *      },
     *      methods = {"GET"}
     * )
     */
    public function removeAction($id)
    {
        $user = $this->getUser();
        $this
            ->get('gitsplit.repository_manager')
            ->removeRepository(
                $this->getUser(),
                $id
            );

        $repositoriesPlain = json_decode($user->getRepositoriesPlain(), true);
        if ($repositoriesPlain[$id]) {

            $repositoriesPlain[$id]['enabled'] = false;
        }
        $user->setRepositoriesPlain(json_encode($repositoriesPlain));
        $this
            ->get('gitsplit.object_manager.user')
            ->flush($user);

        return $this->redirectToRoute('gitsplit_home');
    }

    /**
     * Load repository
     *
     * @Route(
     *      path = "/repository/ping/{id}",
     *      name = "gitsplit_repository_ping",
     *      requirements = {
     *          "id" = "\d+",
     *      },
     *      methods = {"GET"}
     * )
     */
    public function pingAction($repositoryId)
    {
        die('ping');
    }

    /**
     * Load repository
     *
     * @Route(
     *      path = "/repository/{id}",
     *      name = "gitsplit_repository_view",
     *      requirements = {
     *          "id" = "\d+",
     *      },
     *      methods = {"GET"}
     * )
     *
     * @EntityAnnotation(
     *      class = "Gitsplit\RepositoryBundle\Entity\Repository",
     *      name = "repository",
     *      mapping = {
     *          "id": "~id~"
     *      }
     * )
     */
    public function viewAction(Repository $repository)
    {
        return $this->render(
            "GitsplitWebBundle:Repository:view.html.twig",
            [
                'repository' => $repository,
            ]
        );
    }
}
 