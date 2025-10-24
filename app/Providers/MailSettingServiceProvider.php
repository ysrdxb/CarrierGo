<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SettingService;

class MailSettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $setting = SettingService::getSettings();

            if ($setting) {
                config(["mail.mailers.smtp.{$setting->key}" => $setting->value]);
                config(['mail.from.address' => $setting->mail_from_address]);
                config(['mail.from.name' => $setting->mail_from_name]);
            }
        } catch (\Exception $e) {
            // Settings table doesn't exist yet (e.g., during migrations)
            // This is safe to ignore during initial setup
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
