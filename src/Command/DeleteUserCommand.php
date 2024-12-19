<?php

namespace App\Command;

use App\Service\ArtifactoryAuthService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteUserCommand extends Command
{
    protected static $defaultName = 'user:delete';

    private $authService;

    public function __construct(ArtifactoryAuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete a user from Artifactory.')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user to be deleted.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $baseUrl = $_ENV['ARTIFACTORY_BASE_URL'];

        if (!$baseUrl) {
            $output->writeln("<error>Artifactory Base URL is not set. Please set the ARTIFACTORY_BASE_URL environment variable.</error>");
            return Command::FAILURE;
        }

        try {
            $response = $this->authService->deleteUser($username, $baseUrl);
            $output->writeln("<info>{$response}</info>");
        } catch (\Exception $e) {
            $output->writeln("<error>Failed to delete user: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
