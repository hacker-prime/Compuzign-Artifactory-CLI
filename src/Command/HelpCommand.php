<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelpCommand extends Command
{
    protected static $defaultName = 'help:menu';

    protected function configure()
    {
        $this
            ->setDescription('Displays all functions and usage of the CLI.')
            ->setHelp('This command shows a detailed help menu with all available commands and their descriptions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Compuzign Artifactory CLI - Help Menu</info>");
        $output->writeln("Available Commands:");
        $output->writeln("  auth:login       Authenticate with Artifactory using username/password.");
        $output->writeln("  auth:show-token  Display the current stored Artifactory token.");
        $output->writeln("  user:create      Create a new user in Artifactory.");
        $output->writeln("  user:delete      Delete a user from Artifactory.");
        $output->writeln("  system:version   Retrieve and display the Artifactory system version.");
        $output->writeln("\nRun `php bin/console <command> --help` for more details on each command.");
        
        return Command::SUCCESS;
    }
}
