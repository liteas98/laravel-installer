<?php

namespace Liteas98\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Liteas98\LaravelInstaller\Helpers\DatabaseManager;
use Liteas98\LaravelInstaller\Helpers\InstalledFileManager;

class UpdateController extends Controller
{
    use \Liteas98\LaravelInstaller\Helpers\MigrationsHelper;

    public function welcome(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('vendor.installer.update.welcome');
    }

    public function overview(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();

        return view('vendor.installer.update.overview', ['numberOfUpdatesPending' => count($migrations) - count($dbMigrations)]);
    }

    public function database(): \Illuminate\Http\RedirectResponse
    {
        $databaseManager = new DatabaseManager;
        $response = $databaseManager->migrateAndSeed();

        return redirect()->route('LaravelUpdater::final')
                         ->with(['message' => $response]);
    }

    public function finish(InstalledFileManager $fileManager): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $fileManager->update();

        return view('vendor.installer.update.finished');
    }
}
