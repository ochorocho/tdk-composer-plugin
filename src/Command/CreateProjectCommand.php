<?php

declare(strict_types=1);

namespace Ochorocho\TdkComposer\Command;

use Composer\Command\BaseCommand;
use Composer\IO\IOInterface;
use Ochorocho\TdkComposer\Service\GitService;
use Ochorocho\TdkComposer\Service\HookService;
use Ochorocho\TdkComposer\Service\ValidationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class CreateProjectCommand extends BaseCommand
{
    protected OutputInterface $output;

    protected function configure()
    {
        $this
            ->setName('tdk:create-project')
            ->addOption('username', 'u', InputOption::VALUE_OPTIONAL, 'Gerrit username')
            ->addOption('project-name', 'p', InputOption::VALUE_OPTIONAL, 'DDEV project name')
            ->addOption('commit-template', 't', InputOption::VALUE_OPTIONAL, 'Git commit template file')
            ->setDescription('Create composer based core install for contribution')
            ->setHelp(
                <<<EOT
 Create composer based core install for contribution
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Clone Repository
        $cloneInput = new ArrayInput([
            'command' => 'tdk:git',
            'action' => 'clone',
        ]);
        $this->getApplication()->doRun($cloneInput, $output);

        // Set git config
        $gerritInput = [
            'command' => 'tdk:git',
            'action' => 'config',
        ];
        if(!empty($input->getOption('username'))) {
            $gerritInput['--username'] = $input->getOption('username');
        }
        $this->getApplication()->doRun(new ArrayInput($gerritInput), $output);

        // Add hooks
        $hookInput = [
            'command' => 'tdk:hooks',
            'action' => 'create',
            '--force' => true,
        ];
        $this->getApplication()->doRun(new ArrayInput($hookInput), $output);

        // DDEV
        $ddevInput = [
            'command' => 'tdk:ddev',
        ];
        if(!empty($input->getOption('project-name'))) {
            $ddevInput['--project-name'] = $input->getOption('project-name');
        }
        $this->getApplication()->doRun(new ArrayInput($ddevInput), $output);

        // Set git template
        $templateInput = [
            'command' => 'tdk:git',
            'action' => 'template',
        ];
        if(!empty($input->getOption('project-name'))) {
            $templateInput['--file'] = $input->getOption('commit-template');
        }

        $this->getApplication()->doRun(new ArrayInput($templateInput), $output);

        return Command::SUCCESS;
    }
}
