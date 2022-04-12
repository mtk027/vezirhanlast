<?php

namespace App\Providers;

use App\Helpers\Helper;
use App\Models\File;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('settings')) {
            foreach (Setting::where('language_id', Helper::getLanguageId())->get() as $setting) {
                if ($setting->type != "image") {
                    Config::set('settings.' . $setting->group . '.' . $setting->key, $setting->value);
                } else {
                    $path = File::where('slug', $setting->value)->first();
                    if ($path) {
                        $path = $path->path;
                        Config::set('settings.' . $setting->group . '.' . $setting->key, $path);
                    }
                }
            }
        }
        if (session('locale') == null) {
            session()->put('locale', 'tr');
        }

        App::setLocale(session()->get('locale'));

        Schema::defaultstringLength(191);
        
    }
}
