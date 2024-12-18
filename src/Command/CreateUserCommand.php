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

    public function __construct()
    {
        parent::__construct();
        $this->authService = new ArtifactoryAuthService(new Client());
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
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');
        $isAdmin = $input->getOption('admin');

        $userData = [
            'name' => $username,
            'password' => $password,
            'email' => $email,
            'admin' => $isAdmin
        ];

        try {
            $response = $this->authService->createUser($userData);
            $output->writeln("<info>User \"{$username}\" created successfully!</info>");
        } catch (\Exception $e) {
            $output->writeln("<error>Failed to create user: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
