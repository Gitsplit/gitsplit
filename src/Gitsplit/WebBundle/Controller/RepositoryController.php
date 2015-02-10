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

use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Gitsplit\EngineBundle\Entity\Suite;
use Gitsplit\EngineBundle\Entity\Work;
use Gitsplit\RepositoryBundle\Entity\Repository;
use Mmoreram\ControllerExtraBundle\Annotation\Entity as EntityAnnotation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RepositoryController
 *
 * @Route(
 *      path = "/repositor",
 * )
 */
class RepositoryController extends Controller
{
    /**
     * Reload repositories
     *
     * @Route(
     *      path = "ies/reload",
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
     * View repository
     *
     * @Route(
     *      path = "y/{id}",
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
            ":Repository:view.html.twig",
            [
                'repository' => $repository,
            ]
        );
    }

    /**
     * View repository
     *
     * @Route(
     *      path = "y/{id}/build/{suiteId}",
     *      name = "gitsplit_repository_view_suite",
     *      requirements = {
     *          "id" = "\d+",
     *          "suiteId" = "\d+",
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
    public function viewSuiteAction(
        Repository $repository,
        $suiteId
    )
    {
        $suite = $this
            ->get('gitsplit.repository.suite')
            ->findOneBy([
                'id'         => $suiteId,
                'repository' => $repository,
            ]);

        if (!($suite instanceof Suite)) {

            throw new EntityNotFoundException('Suite not found');
        }

        return $this->render(
            ":Repository:viewSuite.html.twig",
            [
                'repository' => $repository,
                'suite'      => $suite
            ]
        );
    }

    /**
     * View repository
     *
     * @Route(
     *      path = "y/{id}/build/{suiteId}/work/{workId}",
     *      name = "gitsplit_repository_view_work",
     *      requirements = {
     *          "id" = "\d+",
     *          "suiteId" = "\d+",
     *          "workId" = "\d+",
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
    public function viewWorkAction(
        Repository $repository,
        $suiteId,
        $workId
    )
    {
        $work = $this
            ->get('gitsplit.repository.work')
            ->findOneBy([
                'id'         => $workId,
                'suite'      => $suiteId,
            ]);

        if (!($work instanceof Work)) {

            throw new EntityNotFoundException('Work not found');
        }

        return $this->render(
            ":Repository:viewWork.html.twig",
            [
                'repository' => $repository,
                'suite'      => $work->getSuite(),
                'work'       => $work,
            ]
        );
    }

    /**
     * Load repository
     *
     * @param Request $request Request
     * @param string  $id      Repository id
     *
     * @return RedirectResponse Back to referrer
     *
     * @Route(
     *      path = "y/{id}/add",
     *      name = "gitsplit_repository_add",
     *      requirements = {
     *          "id" = "\d+",
     *      },
     *      methods = {"GET"}
     * )
     */
    public function addAction(Request $request, $id)
    {
        $user = $this->getUser();

        try {
            $this
                ->get('gitsplit.repository_manager')
                ->addRepository(
                    $this->getUser(),
                    (int)$id
                );

            $repositoriesPlain = json_decode($user->getRepositoriesPlain(), true);

            foreach ($repositoriesPlain as $organizationName => $organizationPlain) {

                if (isset($organizationPlain[$id])) {

                    $repositoriesPlain[$organizationName][$id]['enabled'] = true;
                    break;
                }
            }

            $user->setRepositoriesPlain(json_encode($repositoriesPlain));
            $this
                ->get('gitsplit.object_manager.user')
                ->flush($user);
        } catch (Exception $e) {

            // Silent pass
        }

        $referer = $request
            ->headers
            ->get('referer');

        return new RedirectResponse($referer);
    }

    /**
     * Load repository
     *
     * @param Request    $request    Request
     * @param Repository $repository Repository
     *
     * @return RedirectResponse Back to referrer
     *
     * @Route(
     *      path = "y/{id}/remove",
     *      name = "gitsplit_repository_remove",
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
    public function removeAction(
        Request $request,
        Repository $repository
    )
    {
        $user = $this->getUser();

        try {
            $repositoryId = $repository->getId();
            $this
                ->get('gitsplit.repository_manager')
                ->removeRepository($repository);

            $repositoriesPlain = json_decode($user->getRepositoriesPlain(), true);
            foreach ($repositoriesPlain as $organizationName => $organizationPlain) {

                if (isset($organizationPlain[$repositoryId])) {
                    $repositoriesPlain[$organizationName][$repositoryId]['enabled'] = false;
                    break;
                }
            }
            $user->setRepositoriesPlain(json_encode($repositoriesPlain));
            $this
                ->get('gitsplit.object_manager.user')
                ->flush($user);

        } catch (Exception $e) {

            // Silent pass
        }

        $referer = $request
            ->headers
            ->get('referer');

        return new RedirectResponse($referer);
    }

    /**
     * Load repository
     *
     * @Route(
     *      path = "y/{id}/ping",
     *      name = "gitsplit_repository_ping",
     *      requirements = {
     *          "id" = "\d+",
     *      },
     *      methods = {"GET"}
     * )
     */
    public function pingAction($id)
    {
        die('ping');
    }
}
