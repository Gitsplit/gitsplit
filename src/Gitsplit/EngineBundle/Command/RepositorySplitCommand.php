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

namespace Gitsplit\EngineBundle\Command;

use Gitsplit\EngineBundle\Entity\Work;
use Mmoreram\RSQueueBundle\Command\ConsumerCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * Class RepositorySplitCommand
 */
class RepositorySplitCommand extends ConsumerCommand
{
    /**
     * Configuration method
     */
    protected function configure()
    {
        $this
            ->setName('gitsplit:consumer:split')
            ->setDescription('Gitsplit split consumer');;

        parent::configure();
    }

    /**
     * Relates queue name with appropriated method
     */
    public function define()
    {
        $this->addQueue('splits', 'splitRepository');
    }

    /**
     * Consume method with retrieved queue value
     *
     * Payload:
     *
     *
     * @param InputInterface  $input   An InputInterface instance
     * @param OutputInterface $output  An OutputInterface instance
     * @param Mixed           $payload Data retrieved and unserialized from queue
     */
    protected function splitRepository(
        InputInterface $input,
        OutputInterface $output,
        $payload
    )
    {
        $repository = $payload['repository'];
        $path = $payload['path'];
        $remote = $payload['remote'];
        $workId = $payload['work_id'];
        $token = $payload['token'];
        $branch = $payload['short_branch'];
        $output->writeln("[Work][$workId] Started");

        $workObjectManager = $this
            ->getContainer()
            ->get('gitsplit.object_manager.work');

        $work = $this
            ->getContainer()
            ->get('gitsplit.repository.work')
            ->find($workId);

        if (!($work instanceof Work)) {

            $output->writeln('Work not found with id ' . $workId);
            exit(-1);
        }

        $work->setStatus(Work::STATUS_ACTIVE);
        $workObjectManager->flush($work);

        $splitPath = realpath($this
                ->getContainer()
                ->get('kernel')
                ->getRootDir() . '/../bin/split.sh');

        $generator = new SecureRandom();
        $randomFolder = '/tmp/gitsplit-box-' . bin2hex($generator->nextBytes(10));

        $process = (new ProcessBuilder([
            $splitPath,
            $randomFolder,
            $repository,
            $path,
            $remote,
            $token,
            $branch,
        ]))->getProcess();
        $process->setTimeout(300);
        $result = $process->run(
            function ($type, $buffer) use ($work, $workObjectManager, $output, $workId) {
                $work->appendLog($buffer);
                $workObjectManager->flush($work);
                $output->writeln(strip_tags($buffer));
            }
        );

        $work
            ->setStatus(Work::STATUS_FINISHED)
            ->setResult($result);

        $workObjectManager->flush($work);
        $output->writeln("[Work][$workId] Finished with [$result]");

        exit(0);
    }
}
