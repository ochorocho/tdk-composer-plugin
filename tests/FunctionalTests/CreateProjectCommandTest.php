<?php

namespace Ochorocho\TdkComposer\FunctionalTests;

use Composer\Console\Application;
use Ochorocho\TdkComposer\Command\CreateProjectCommand;
use Ochorocho\TdkComposer\Command\DoctorCommand;
use Ochorocho\TdkComposer\Service\BaseService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateProjectCommandTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new CreateProjectCommand());

        $command = $application->find('tdk:create-project');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--username' => 'ochorocho',
            '--project-name' => 'typo3-core-test-tdk',
            '--commit-template' => '.gitmessage.txt',
        ]);

        $this->assertFileExists(BaseService::CORE_DEV_FOLDER . '/.git/hooks/commit-msg');
        $this->assertFileExists(BaseService::CORE_DEV_FOLDER . '/.git/hooks/pre-commit');

        $isExecutable = is_executable(BaseService::CORE_DEV_FOLDER . '/.git/hooks/commit-msg');
        $this->assertTrue($isExecutable, 'The file is not executable.');

        $isExecutable = is_executable(BaseService::CORE_DEV_FOLDER . '/.git/hooks/pre-commit');
        $this->assertTrue($isExecutable, 'The file is not executable.');
    }

//    public function doctorExecute()
//    {
//        $application = new Application();
//        $application->add(new DoctorCommand());
//
//        $command = $application->find('tdk:doctor');
//        $commandTester = new CommandTester($command);
//
//        $commandTester->execute([]);
//        $output = $commandTester->getDisplay();
//
//        $this->assertStringCont ainsString('✔ Repository exists on commit', $output);
//        $this->assertStringContainsString('✔ All hooks are in place', $output);
//        $this->assertStringContainsString('✔ Git "remote.origin.pushurl" seems correct', $output);
//        $this->assertStringContainsString('✔ Git "commit.template" is set to', $output);
//        $this->assertStringContainsString('✔ Vendor folder exists.', $output);
//    }
}
