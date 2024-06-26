<?php

namespace Liteas98\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Liteas98\LaravelInstaller\Events\LaravelInstallerFinished;
use Liteas98\LaravelInstaller\Helpers\EnvironmentManager;
use Liteas98\LaravelInstaller\Helpers\FinalInstallManager;
use Liteas98\LaravelInstaller\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param \Liteas98\LaravelInstaller\Helpers\InstalledFileManager $fileManager
     * @param \Liteas98\LaravelInstaller\Helpers\FinalInstallManager $finalInstall
     * @param \Liteas98\LaravelInstaller\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);

        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
