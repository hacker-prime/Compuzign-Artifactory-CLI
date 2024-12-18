<?php

namespace App\Command;

use App\Service\ArtifactoryAuthService;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class LoginCommand extends Command
{
    protected static $defaultName = 'auth:login';

    private $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new ArtifactoryAuthService(new Client());
    }

    protected function configure()
    {
        $this->setDescription('Authenticate with Artifactory using username and password.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        // Prompt for username
        $usernameQuestion = new Question('Enter Artifactory username: ');
        $username = $helper->ask($input, $output, $usernameQuestion);

        // Prompt for password with better handling for hidden input
        $passwordQuestion = new Question('Enter Artifactory password: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false); // Prevents issues with unsupported terminals
        $password = $helper->ask($input, $output, $passwordQuestion);

        // Prompt for base URL
        $urlQuestion = new Question('Enter Artifactory Base URL (e.g., https://your-instance.jfrog.io): ');
        $baseUrl = $helper->ask($input, $output, $urlQuestion);

        try {
            $token = $this->authService->authenticate($username, $password, $baseUrl);
            $output->writeln("<info>Authentication successful. Token stored securely.</info>");
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

?>