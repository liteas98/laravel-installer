<?php

namespace Liteas98\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;

class WelcomeController extends Controller
{
    public function welcome(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('vendor.installer.welcome');
    }
}
