<?php

namespace Ochorocho\TdkComposer\FunctionalTests;

use Composer\Console\Application;
use Ochorocho\TdkComposer\Command\HelpCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class HelpCommandTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new HelpCommand());

        $command = $application->find('tdk:help');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('https://docs.typo3.org/m/typo3/guide-contributionworkflow/master/en-us/Account/GerritAccount.html',
            $output);
        $this->assertStringContainsString('https://docs.typo3.org/m/typo3/guide-contributionworkflow/master/en-us/Setup/Git/Index.html',
            $output);
        $this->assertStringContainsString('https://docs.typo3.org/m/typo3/guide-contributionworkflow/master/en-us/Setup/SetupIde.html',
            $output);
        $this->assertStringContainsString('https://docs.typo3.org/m/typo3/guide-contributionworkflow/master/en-us/Testing/Index.html',
            $output);
        $this->assertStringContainsString('https://review.typo3.org/settings/#SSHKeys', $output);
    }
}
