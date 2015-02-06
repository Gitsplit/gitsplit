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

namespace Gitsplit\RepositoryBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Gitsplit\RepositoryBundle\Entity\Repository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * Class RepositoryHookEventListener
 */
class RepositoryHookEventListener
{
    /**
     * @var ContainerInterface
     *
     * Container
     */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Pre persist
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->addGithubHook($args);
    }

    /**
     * Pre remove
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->removeGithubHook($args);
    }

    /**
     * On repository update or persist
     */
    protected function addGithubHook(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Repository) {

            $generator = new SecureRandom();
            $webhookSecret = bin2hex($generator->nextBytes(10));

            $response = $this
                ->container
                ->get('gitsplit.repository_manager')
                ->getAuthenticatedClient($entity->getUser())
                ->api('repo')
                ->hooks()
                ->create($entity->getOwner(), $entity->getName(), [
                    'name'   => 'web',
                    'config' => [
                        "url"          => "http://gitsplit.com/push",
                        "content_type" => "json",
                        "secret"       => $webhookSecret,
                    ],
                    'events' => ['push', 'create']
                ]);

            $entity
                ->setWebhookId($response['id'])
                ->setWebhookSecret($webhookSecret);
        }
    }

    /**
     * On repository update or persist
     */
    protected function removeGithubHook(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Repository) {

            $this
                ->container
                ->get('gitsplit.repository_manager')
                ->getAuthenticatedClient($entity->getUser())
                ->api('repo')
                ->hooks()
                ->remove($entity->getOwner(), $entity->getName(), $entity->getWebhookId());
        }
    }
}
