<?php

declare(strict_types=1);

namespace Ochorocho\TdkComposer\Command;

use Composer\Command\BaseCommand;
use Composer\Repository\CompositeRepository;
use Composer\Repository\RepositoryFactory;
use Composer\Util\Filesystem;
use Ochorocho\TdkComposer\Service\BaseService;
use Ochorocho\TdkComposer\Service\ComposerService;
use Ochorocho\TdkComposer\Service\GitService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Finder\Finder;

final class RequireCommand extends BaseCommand
{
    protected OutputInterface $output;

    protected function configure()
    {
        $this
            ->setName('tdk:require')
            ->setDescription('Require packages from local repository')
            ->addArgument('packages', InputArgument::OPTIONAL, 'Packages to require.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packages = $input->getArgument('packages');
        $composerService = new ComposerService();
        $packageNames = $composerService->getCorePackageNames();

        if (!(count($packageNames) > 0)) {
            $cloneInput = new ArrayInput([
                'command' => 'tdk:git',
                'action' => 'clone',
            ]);

            $this->getApplication()->doRun($cloneInput, $output);
            $packageNames = $composerService->getCorePackageNames();
        }

        array_unshift($packageNames, 'all');

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select packages you want to require (defaults to "all")',
            $packageNames,
            'all'
        );
        $question->setMultiselect(true);
        $question->setAutocompleterValues($packageNames);
        $question->setErrorMessage('Package %s is invalid.');
        $selectedPackages = $helper->ask($input, $output, $question);

        // $composerService->addCoreRepository($this->requireComposer());
        //$coreRepository = $this->requireComposer()->getRepositoryManager()->createRepository('path', ['url' => 'typo3-core/typo3/sysext/*'], 'typo3-core-packages');
        // var_dump($coreRepository->getPackages());
        //$this->requireComposer()->getRepositoryManager()->addRepository($coreRepository);







        // @todo: This could be an option to create a repository on the fly
        //        and add the local core packages to the repository.
        //        $localRepo = $this->requireComposer()->getRepositoryManager()->getLocalRepository();
        //        $localRepo->addPackage('');
        //        $rm = $this->requireComposer()->getRepositoryManager();
        //        $this->requireComposer()->getRepositoryManager()->addRepository(new CompositeRepository(RepositoryFactory::defaultRepos($this->getIO(), $config, $rm)));

        if(in_array('all', $selectedPackages) ?? false) {
            unset($packageNames[array_search('all', $packageNames)]);
            $installPackages = array_map(function($name) { return $name . ':@dev'; }, $packageNames);
        } else {
            $minimalRequiredPackages = [
                "typo3/cms-backend",
                "typo3/cms-core",
                "typo3/cms-extbase",
                "typo3/cms-extensionmanager",
                "typo3/cms-filelist",
                "typo3/cms-fluid",
                "typo3/cms-frontend",
                "typo3/cms-install",
                "typo3/cms-styleguide",
            ];

            $installPackages = array_map(function($name) { return $name . ':@dev'; }, array_merge($minimalRequiredPackages, $selectedPackages));
        }

         // packages
        $ddevInput = new ArrayInput([
            'command' => 'req',
            'packages' => $installPackages,
        ]);

        $this->getApplication()->doRun($ddevInput, $output);

        return Command::SUCCESS;
    }
}
