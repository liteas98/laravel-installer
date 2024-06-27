<?php

namespace Liteas98\LaravelInstaller\Middleware;

use Closure;

class canUpdate
{
    use \Liteas98\LaravelInstaller\Helpers\MigrationsHelper;

    public function handle($request, Closure $next)
    {
        $updateEnabled = filter_var(config('installer.updaterEnabled'), FILTER_VALIDATE_BOOLEAN);
        switch ($updateEnabled) {
            case true:
                $canInstall = new canInstall;
                if (! $canInstall->alreadyInstalled()) {
                    return redirect()->route('LaravelInstaller::welcome');
                }

                if ($this->alreadyUpdated()) {
                    abort(404);
                }
                break;

            case false:
            default:
                abort(404);
                break;
        }

        return $next($request);
    }

    public function alreadyUpdated(): bool
    {
        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();

        if (count($migrations) == count($dbMigrations)) {
            return true;
        }

        return false;
    }
}
