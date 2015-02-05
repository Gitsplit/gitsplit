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

namespace Gitsplit\EngineBundle\Controller;

use Gitsplit\RepositoryBundle\Entity\Repository;
use Gitsplit\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Parser;

/**
 * Class SplitController
 */
class SplitController extends Controller
{
    /**
     * Load repository
     *
     * @Route(
     *      path = "/push",
     *      name = "gitsplit_push",
     *      methods = {"GET"}
     * )
     */
    public function splitAction(Request $request)
    {
        $request = Request::createFromGlobals();
        $content = $request->getContent();
        $jsonContent = json_decode($content, true);

        /**
         * None json format
         */
        if (!$jsonContent) {

            echo 'Could not read JSON payload. Aborting';
            exit(-1);
        }

        /**
         * Looking for the repository associated to this webhook
         */
        $repositoryId = $jsonContent->repository->id;
        $repository = $this
            ->get('gitsplit.repository.repository')
            ->findOneBy([
                'id' => $repositoryId
            ]);

        if (!($repository instanceof Repository)) {

            echo 'Repository not found';
            exit(-1);
        }

        /**
         * Checking the signature
         *
         * Signature has to be computed according to
         * https://developer.github.com/webhooks/securing/
         */
        $computedSignature = 'sha1=' . hash_hmac('sha1', $content, $repository->getWebhookSecret());
        $githubSignature = $request->headers->get('X-Hub-Signature');
        echo 'GitHub webhook signature is: ' . $githubSignature;

        // Secure compare signatures before proceeding
        if (!$this->secureCompare($githubSignature, $computedSignature)) {

            echo 'Signature mismatch. Aborting.';
            exit(-1);
        }

        $githubEvent = $request
            ->headers
            ->get('X-Github-Event');

        if (
            ('push' === $githubEvent && 'refs/heads/master' === $jsonContent->ref) ||
            ("create" == $githubEvent && "tag" == $jsonContent->ref_type)
        ) {

            $splitDefinition = $this->getRepositorySplitDefinition($repository);
            $this->enqueueSplit(
                $repository,
                $splitDefinition
            );
        }

        return new Response('');
    }

    /**
     * Load split definition
     *
     * @param Repository $repository Repository
     *
     * @return array Split definition
     */
    public function getRepositorySplitDefinition(Repository $repository)
    {
        $url = 'https://raw.githubusercontent.com/' .
            $repository->getOwner() . '/' .
            $repository->getName() . '/master/.gitsplit.yml';

        $yaml = new Parser();
        $data = $yaml->parse(file_get_contents($url));

        return $data;
    }

    /**
     * Securely compares two strings to avoid a time based attack.
     *
     * @see http://codereview.stackexchange.com/questions/13512/constant-time-string-comparision-in-php-to-prevent-timing-attacks
     * @see http://rubydoc.info/github/rack/rack/master/Rack/Utils.secure_compare
     *
     * @param string $original    the known string
     * @param string $destination the string to compare to
     *
     * @return boolean
     */
    protected function secureCompare($original, $destination)
    {
        if (strlen($original) != strlen($destination)) {
            return false;
        }
        $originalSplitted = str_split($original);
        $destinationSplitted = str_split($destination);
        $i = -1;
        $stringsAreEquals = array_reduce(
            $destinationSplitted,
            function ($yieldResult, $currentChar) use ($originalSplitted, &$i) {
                $i++;

                // At first iteration, value of $yieldResult is irrelevant
                return (
                is_null($yieldResult) ? true : $yieldResult
                    && $currentChar === $originalSplitted[$i]
                );
            }
        );

        return $stringsAreEquals;
    }

    /**
     * Add to redis queue
     *
     * @param array $splitDefinition
     */
    protected function enqueueSplit(
        Repository $repository,
        array $splitDefinition
    )
    {
        $suiteObjectManager = $this->get('gitsplit.object_manager.suite');
        $workObjectManager = $this->get('gitsplit.object_manager.work');

        $suite = $this
            ->get('gitsplit.factory.suite')
            ->create($repository->getUser(), $repository);
        $suiteObjectManager->persist($suite);
        $suiteObjectManager->flush($suite);

        foreach ($splitDefinition['split'] as $path => $remote) {

            $work = $this
                ->get('gitsplit.factory.work')
                ->create($suite, $path, $remote);

            $workObjectManager->persist($work);
            $workObjectManager->flush($work);

            $this
                ->container
                ->get("rs_queue.producer")
                ->produce("splits", [
                    'repository' => $repository->getSshUrl(),
                    'path'       => $path,
                    'remote'     => $remote,
                    'work_id'    => $work->getId(),
                ]);
        }
    }
}
 