<?php

namespace Liteas98\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Liteas98\LaravelInstaller\Helpers\PermissionsChecker;

class PermissionsController extends Controller
{
    protected PermissionsChecker $permissions;

    public function __construct(PermissionsChecker $checker)
    {
        $this->permissions = $checker;
    }

    public function permissions(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $permissions = $this->permissions->check(
            config('installer.permissions')
        );

        return view('vendor.installer.permissions', compact('permissions'));
    }
}
