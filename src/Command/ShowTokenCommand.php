<?php
namespace App\Command;

use App\Service\ArtifactoryAuthService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowTokenCommand extends Command
{
    protected static $defaultName = 'auth:show-token';

    private $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new ArtifactoryAuthService(new \GuzzleHttp\Client());
    }

    protected function configure()
    {
        $this->setDescription('Show the currently stored Artifactory token.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $token = $this->authService->getToken();
            $output->writeln("<info>Stored Token: {$token}</info>");
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

?>