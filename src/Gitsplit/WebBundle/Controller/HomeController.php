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

use Doctrine\Common\Collections\Collection;
use Gitsplit\RepositoryBundle\Entity\Repository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomeController
 */
class HomeController extends Controller
{
    /**
     * Home
     *
     * @Route(
     *      path = "/",
     *      name = "gitsplit_home",
     *      methods = {"GET"}
     * )
     */
    public function viewAction()
    {
        $user = $this->getUser();
        $repositoriesPlain = json_decode($user->getRepositoriesPlain(), true);

        if (null === $repositoriesPlain) {

            $repositoriesPlain = [];
            $remoteRepositories = $this
                ->get('gitsplit.repository_manager')
                ->loadAllRepositories($this->getUser());

            foreach ($remoteRepositories as $remoteRepository) {

                if ($user->getUsername() !== $remoteRepository['owner']['login']) {

                    continue;
                }

                $remoteRepositoryId = $remoteRepository['id'];
                $repositoriesPlain[$remoteRepositoryId] = [
                    'id'     => $remoteRepository['id'],
                    'url'     => $remoteRepository['html_url'],
                    'name'    => $remoteRepository['name'],
                    'enabled' => false,
                ];
            }

            $repositories = $this
                ->getUser()
                ->getRepositories();

            if ($repositories instanceof Collection) {
                /**
                 * @var Repository $repository
                 */
                foreach ($repositories as $repository) {

                    $repositoriesPlain[$repository->getId()]['enabled'] = true;
                }
            }

            $user->setRepositoriesPlain(json_encode($repositoriesPlain));
            $this
                ->get('gitsplit.object_manager.user')
                ->flush($user);
        }

        return $this->render(
            "GitsplitWebBundle:Home:view.html.twig",
            [
                'repositories' => $repositoriesPlain,

            ]
        );
    }
}
