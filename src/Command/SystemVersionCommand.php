<?php

namespace App\Command;

use App\Service\ArtifactoryAuthService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SystemVersionCommand extends Command
{
    protected static $defaultName = 'system:version';

    private $authService;

    public function __construct(ArtifactoryAuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }

    protected function configure()
    {
        $this->setDescription('Retrieve and display the Artifactory system version.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $baseUrl = $_ENV['ARTIFACTORY_BASE_URL'];

        if (!$baseUrl) {
            $output->writeln("<error>Artifactory Base URL is not set. Please set the ARTIFACTORY_BASE_URL environment variable.</error>");
            return Command::FAILURE;
        }

        try {
            $versionInfo = $this->authService->getSystemVersion($baseUrl);
            $output->writeln("<info>Artifactory System Version: {$versionInfo['version']}</info>");
            $output->writeln("<info>Revision: {$versionInfo['revision']}</info>");
        } catch (\Exception $e) {
            $output->writeln("<error>Failed to retrieve system version: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
