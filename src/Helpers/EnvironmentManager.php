<?php

namespace Liteas98\LaravelInstaller\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnvironmentManager
{
    private string $envPath;

    private string $envExamplePath;

    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    public function getEnvContent(): bool|string
    {
        if (! file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        return file_get_contents($this->envPath);
    }

    public function getEnvPath(): string
    {
        return $this->envPath;
    }

    public function getEnvExamplePath(): string
    {
        return $this->envExamplePath;
    }

    public function saveFileClassic(Request $input): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        $message = trans('installer_messages.environment.success');

        try {
            file_put_contents($this->envPath, $input->get('envConfig'));
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }

    public function saveFileWizard(Request $request): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        $results = trans('installer_messages.environment.success');

        $envFileData =
        'APP_NAME=\''.$request->app_name."'\n".
        'APP_ENV='.$request->environment."\n".
        'APP_KEY='.'base64:'.base64_encode(Str::random(32))."\n".
        'APP_DEBUG='.$request->app_debug."\n".
        'APP_LOG_LEVEL='.$request->app_log_level."\n".
        'APP_URL='.$request->app_url."\n\n".
        'PURCHASE_CODE=' . $request->purchase_code . "\n\n" .
        'DB_CONNECTION='.$request->database_connection."\n".
        'DB_HOST='.$request->database_hostname."\n".
        'DB_PORT='.$request->database_port."\n".
        'DB_DATABASE='.$request->database_name."\n".
        'DB_USERNAME='.$request->database_username."\n".
        'DB_PASSWORD='.$request->database_password."\n\n".
        'BROADCAST_DRIVER='.$request->broadcast_driver."\n".
        'CACHE_DRIVER='.$request->cache_driver."\n".
        'SESSION_DRIVER='.$request->session_driver."\n".
        'QUEUE_DRIVER='.$request->queue_driver."\n\n".
        'REDIS_HOST='.$request->redis_hostname."\n".
        'REDIS_PASSWORD='.$request->redis_password."\n".
        'REDIS_PORT='.$request->redis_port."\n\n".
        'MAIL_DRIVER='.$request->mail_driver."\n".
        'MAIL_HOST='.$request->mail_host."\n".
        'MAIL_PORT='.$request->mail_port."\n".
        'MAIL_USERNAME='.$request->mail_username."\n".
        'MAIL_PASSWORD='.$request->mail_password."\n".
        'MAIL_ENCRYPTION='.$request->mail_encryption."\n\n".
        'PUSHER_APP_ID='.$request->pusher_app_id."\n".
        'PUSHER_APP_KEY='.$request->pusher_app_key."\n".
        'PUSHER_APP_SECRET='.$request->pusher_app_secret;

        try {
            file_put_contents($this->envPath, $envFileData);
        } catch (Exception $e) {
            $results = trans('installer_messages.environment.errors');
        }

        return $results;
    }
}
