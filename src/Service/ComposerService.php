<?php

declare(strict_types=1);

namespace Ochorocho\TdkComposer\Service;

use Composer\Composer;
use Composer\Util\Filesystem;
use Composer\Util\ProcessExecutor;
use Symfony\Component\Finder\Finder;

class ComposerService extends BaseService
{
    public function addCoreRepository(Composer $composer): void
    {
        $composer->getConfig()->getConfigSource()->addRepository('typo3-core-packages', ['type' => 'path', 'url' => 'typo3-core/typo3/sysext/*']);
    }

    public function removeCoreRepository(Composer $composer): void
    {
        $composer->getConfig()->getConfigSource()->removeRepository('typo3-core-packages');
    }

    public function getCorePackageNames(): array
    {
        if (!$this->filesystem->exists(BaseService::CORE_DEV_FOLDER . '/typo3/sysext')) {
            return [];
        }

        $finder = new Finder();
        $files = $finder->name('composer.json')->in(BaseService::CORE_DEV_FOLDER . '/typo3/sysext')->depth(1);

        $packageNames = [];
        foreach ($files as $file) {
            $composerJson = json_decode(file_get_contents($file->getPathname()), true, 512, JSON_THROW_ON_ERROR);
            $packageNames[] = $composerJson['name'];
        }

        return $packageNames;
    }
}
