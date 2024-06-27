<?php

namespace Liteas98\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Liteas98\LaravelInstaller\Helpers\RequirementsChecker;

class RequirementsController extends Controller
{
    protected RequirementsChecker $requirements;

    public function __construct(RequirementsChecker $checker)
    {
        $this->requirements = $checker;
    }

    public function requirements(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('installer.requirements')
        );

        return view('vendor.installer.requirements', compact('requirements', 'phpSupportInfo'));
    }
}
