<?php

namespace Liteas98\LaravelInstaller\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Liteas98\LaravelInstaller\Events\EnvironmentSaved;
use Liteas98\LaravelInstaller\Helpers\EnvironmentManager;

class EnvironmentController extends Controller
{
    protected EnvironmentManager $EnvironmentManager;

    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    public function environmentMenu(): \Illuminate\View\View
    {
        return view('vendor.installer.environment');
    }

    public function environmentWizard(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-wizard', compact('envConfig'));
    }

    public function environmentClassic(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-classic', compact('envConfig'));
    }

    public function saveClassic(Request $input, Redirector $redirect): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $message = $this->EnvironmentManager->saveFileClassic($input);

        event(new EnvironmentSaved($input));

        // $itmId="";
        // $token = "";

        // $code = env('PURCHASE_CODE',false);
        // if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $code)) {
        //     $code = false;
        //     $errors = 'Not valid purchase code';
        // } else {

        //     $ch = curl_init();
        //     curl_setopt_array($ch, array(
        //         CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_TIMEOUT => 20,

        //         CURLOPT_HTTPHEADER => array(
        //             "Authorization: Bearer {$token}",
        //             "User-Agent: Verify Purchase Code"
        //         )
        //     ));
        //     $result = curl_exec($ch);
        //     if (isset($result) && isset(json_decode($result,true)['error'])) {
        //         $code = false;
        //         $errors ='Not valid purchase code';
        //     }else{
        //         if (isset($result) && json_decode($result,true)['item']['id'] != $itmId) {
        //             $code = false;
        //             $errors = 'Not valid purchase code';
        //         }
        //     }
        // }

        if (isset($errors)){
            $envConfig = $this->EnvironmentManager->getEnvContent();
            return view('vendor.installer.environment-classic', compact('errors', 'envConfig'));
        }

        return $redirect->route('LaravelInstaller::environmentClassic')
                        ->with(['message' => $message]);
    }

    public function saveWizard(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors($validator->errors());
        }

        if (! $this->checkDatabaseConnection($request)) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'database_connection' => trans('installer_messages.environment.wizard.form.db_connection_failed'),
            ]);
        }

        // $itmId="";
        // $token = "";

        // $code = env('PURCHASE_CODE',false);
        // if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $code)) {
        //     $code = false;
        //     $errors = $validator->errors()->add('purchase_code', 'Not valid purchase code');
        // } else {

        //     $ch = curl_init();
        //     curl_setopt_array($ch, array(
        //         CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_TIMEOUT => 20,

        //         CURLOPT_HTTPHEADER => array(
        //             "Authorization: Bearer {$token}",
        //             "User-Agent: Verify Purchase Code"
        //         )
        //     ));
        //     $result = curl_exec($ch);
        //     if (isset($result) && isset(json_decode($result,true)['error'])) {
        //         $code = false;
        //         $errors = $validator->errors()->add('purchase_code', 'Not valid purchase code');
        //     }else{
        //         if (isset($result) && json_decode($result,true)['item']['id'] != $itmId) {
        //             $code = false;
        //             $errors = $validator->errors()->add('purchase_code', 'Not valid purchase code');
        //         }
        //     }
        // }

        if (isset($errors)){
            return view('vendor.installer.environment-classic', compact('errors'));
        }

        $results = $this->EnvironmentManager->saveFileWizard($request);

        event(new EnvironmentSaved($request));

        return $redirect->route('LaravelInstaller::database')
                        ->with(['results' => $results]);
    }

    private function checkDatabaseConnection(Request $request): bool
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ]),
                ],
            ],
        ]);

        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
