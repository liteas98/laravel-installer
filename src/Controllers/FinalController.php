<?php

namespace Liteas98\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Liteas98\LaravelInstaller\Events\LaravelInstallerFinished;
use Liteas98\LaravelInstaller\Helpers\EnvironmentManager;
use Liteas98\LaravelInstaller\Helpers\FinalInstallManager;
use Liteas98\LaravelInstaller\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);

        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
