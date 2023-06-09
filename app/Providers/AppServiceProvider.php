<?php

namespace App\Providers;

use App\Api\v1\Enums\UserRoles;
use App\Classes\FileUploader;
use App\Classes\Permissions;
use App\Http\Middleware\LocaleMiddleware;
use App\Models\AdminSetting;
use App\Models\Progress;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

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
    public function boot(Dispatcher $events)
    {
        Schema::defaultStringLength(191);

        // permissions
        $this->app->bind('permissions', Permissions::class);
        $this->app->bind('fileUploader', FileUploader::class);

        try {
            $cached_settings = Cache::get('admin_settings', fn() => AdminSetting::saveCache());

            $configs = [];
            $side_bar_left_config = [];
            $side_bar_top_config = [];

            foreach ($cached_settings as $setting) {
                if (str_contains($setting['group'], 'side_bars_left')) {
                    $side_bar_left_config[] = $setting;
                } elseif (str_contains($setting['group'], 'side_bars_top')) {
                    $side_bar_top_config[] = $setting;
                } else {
                    $configs['adminlte.' . $setting['key']] = $setting['value'];
                }
            }

            foreach ($side_bar_left_config as $item) {
                if (!isset($configs['adminlte.' . $item['key']])) {
                    $configs['adminlte.' . $item['key']] = $item['value'];
                } else {
                    if ($item['group'] == 'side_bars_left_side_navbar_text') {
                        $configs['adminlte.' . $item['key']] .= ' ' . $item['value'];
                    } else {
                        $configs['adminlte.' . $item['key']] .= '-' . $item['value'];
                    }
                }
            }

            foreach ($side_bar_top_config as $item) {
                if (!isset($configs['adminlte.' . $item['key']])) {
                    $configs['adminlte.' . $item['key']] = $item['value'];
                } else {
                    $configs['adminlte.' . $item['key']] .= ' ' . $item['value'];
                }
            }

            config($configs);
        } catch (\Exception $exception) {
            Log::info(['app_service_provider_settings' => $exception->getMessage()]);
        }

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $user = auth('web')->user();
            $user->load('role');

            if ($user->role?->slug == UserRoles::STUDENT->value)
            {
                $event->menu->addAfter('progress', [
                    'text'   => ['progress'],
                    'key'    => 'student_progress',
                    'url'    => 'student-progress',
                    'active' => ['student-progress/*'],
                ]);
                $event->menu->addAfter('student_schedule', [
                    'text'   => ['schedule'],
                    'key'    => 'student_schedule',
                    'url'    => 'student-schedule',
                    'active' => ['student-schedule/*'],
                ]);
            } elseif ($user->role?->slug == UserRoles::TEACHER->value) {
                $event->menu->addAfter('progress', [
                    'text'   => ['progress'],
                    'key'    => 'teacher_progress',
                    'url'    => 'teacher-progress',
                    'active' => ['teacher-progress/*'],
                    'label' => Progress::whereHas('student', fn($q) => $q->where('teacher_id', $user->id))
                        ->whereNull('result')->count(),
                ]);

                $event->menu->addAfter('student_schedule', [
                    'text'   => ['schedule'],
                    'key'    => 'teacher_schedule',
                    'url'    => 'teacher-schedule',
                    'active' => ['teacher-schedule/*'],
                ]);
            }

            $event->menu->add([
                'key' => 'lang',
                'text' => mb_strtoupper((app()->getLocale() == 'us') ? 'en' : app()->getLocale()),
                'url' => '',
                'topnav_right' => true,
            ]);

            $locales = LocaleMiddleware::$admin_languages;

            foreach ($locales as $locale) {
                if ($locale == app()->getLocale()) {
                    continue;
                }

                $event->menu->addIn(
                    'lang',
                    [
                        'text' => mb_strtoupper(($locale == 'us') ? 'en' : $locale),
                        'url' => route('locale', $locale),
                    ]
                );
            }
        });
    }
}
