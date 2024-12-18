<?php

namespace App\Command;

use App\Service\ArtifactoryAuthService;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'user:create';

    private $authService;


    public function __construct(ArtifactoryAuthService $authService)
    {
    parent::__construct();
    $this->authService = $authService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a new user in Artifactory.')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the new user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the new user.')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the new user.')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user will be an admin.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Gather input arguments and options
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');
        $isAdmin = $input->getOption('admin');
        $baseUrl = $_ENV['ARTIFACTORY_BASE_URL'];

        if (!$baseUrl) {
            $output->writeln("<error>Artifactory Base URL is not set. Please set the ARTIFACTORY_BASE_URL environment variable.</error>");
            return Command::FAILURE;
        }

        $userData = [
            'name' => $username,
            'password' => $password,
            'email' => $email,
            'admin' => $isAdmin
        ];

        try {
            // Pass userData and baseUrl to createUser method
            $response = $this->authService->createUser($userData, $baseUrl);
            $output->writeln("<info>{$response}</info>");
        } catch (\Exception $e) {
            $output->writeln("<error>Failed to create user: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
