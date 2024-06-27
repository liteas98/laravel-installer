<?php

namespace Liteas98\LaravelInstaller\Helpers;

use Illuminate\Support\Facades\DB;

trait MigrationsHelper
{
    public function getMigrations(): array|bool|string
    {
        $migrations = glob(database_path().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'*.php');

        return str_replace('.php', '', $migrations);
    }

    public function getExecutedMigrations(): \Illuminate\Support\Collection
    {
        return DB::table('migrations')->get()->pluck('migration');
    }
}
