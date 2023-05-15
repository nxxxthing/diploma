<?php

namespace App\Http\Controllers\Backend;

use App\Facades\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminSettingController extends Controller
{
    private $module = 'admin_settings';

    public function index()
    {
        abort_unless(\Gate::allows($this->module.'_access'), 403);
        return view('admin.view.' . $this->module . '.index', $this->getViewData());
    }

    public function update(Request $request)
    {
        abort_unless(\Gate::allows($this->module.'_access'), 403);
        $this->updateAdminTitle($request);
        $this->updateLogo($request);
        $this->saveUserMenu($request);
        $this->saveSideBars($request);

        return redirect()->back();
    }

    public function setDefault()
    {
        abort_unless(\Gate::allows($this->module.'_access'), 403);
        \DB::statement("UPDATE admin_settings
                              SET value = `default`, pure_value = `default_color`
                              WHERE status = true;");

        AdminSetting::saveCache();

        return redirect()->back();
    }

    private function getViewData(): array
    {
        $admin_settings = AdminSetting::where('status', true)->get();
        $groups = $admin_settings->pluck('group')->unique()->map(function ($group) {
            if (
                in_array(
                    $group,
                    [
                        'side_bars_left_side_navbar',
                        'side_bars_left_side_navbar_text',
                        'side_bars_left_side_navbar_text_hover',
                        'side_bars_top_sidebar',
                        'side_bars_top_text'
                    ]
                )
            ) {
                return 'side_bars';
            }

            return $group;
        });

        return [
            'module'         => $this->module,
            'admin_settings' => $admin_settings,
            'groups'         => $groups->unique(),
            'settings'       => $admin_settings
        ];
    }

    private function updateAdminTitle(Request $request)
    {
        $data = $request->only('admin_title');
        foreach ($data['admin_title'] as $key => $setting) {
            if ($key != 'favicon') {
                AdminSetting::where('key', $key)->update([
                    'value'      => $setting,
                    'pure_value' => $setting
                ]);
            } else {
                if ($setting['isRemoveImage'] && File::exists(base_path('public/favicons/favicon.ico'))) {
                    File::delete(base_path('public/favicons/favicon.ico'));
                }

                if (isset($setting['file'])) {
                    $setting['file']->storeAs(
                        'favicons',
                        'favicon.ico'
                    );

                    File::copy(
                        base_path('public/storage/favicons/favicon.ico'),
                        base_path('public/favicons/favicon.ico')
                    );
                }
            }
        }
    }

    private function updateLogo(Request $request)
    {
        $data = $request->only('logo');

        foreach ($data['logo'] as $key => $setting) {
            $model =  AdminSetting::where('key', $key)->first();
            if ($key == 'logo') {
                $model->update([
                    'value'      => $setting,
                    'pure_value' => $setting
                ]);
            } else {
                if ($setting['isRemoveImage']) {
                    FileUploader::delete($model->pure_value);
                    $model->update([
                        'value'      => $model->default,
                        'pure_value' => $model->default
                    ]);
                }

                if (isset($setting['file'])) {
                    $path = FileUploader::putAs($setting['file'], 'image', 'logo', 'logo_img');
                    $model->update([
                        'value'      => 'storage/' . $path,
                        'pure_value' => 'storage/' . $path
                    ]);
                }
            }
        }
    }

    private function saveUserMenu(Request $request)
    {
        $data = $request->only('usermenu');

        foreach ($data['usermenu'] as $key => $setting) {
            $model =  AdminSetting::where('key', $key)->first();

            $pure_value = $key == 'usermenu_header_class' ? AdminSetting::getColorKeyByValue($setting) : $setting;

            $value = $key == 'usermenu_header_class'
                ? str_replace('{value}', $pure_value, $model->replace_template)
                : $setting;

            $model->update([
                'value'      => $value,
                'pure_value' => $pure_value
            ]);
        }
    }

    private function saveSideBars(Request $request)
    {
        $data = $request->only('classes_sidebar', 'classes_topnav');

        $settings = array_merge($data['classes_sidebar'], $data['classes_topnav']);

        foreach ($settings as $key => $setting) {
            $model = AdminSetting::where('group', $key)->first();

            $pure_value = in_array(
                $key,
                [
                    'side_bars_left_side_navbar',
                    'side_bars_top_sidebar',
                    'side_bars_left_side_navbar_text_hover'
                ]
            )
                ? AdminSetting::getColorKeyByValue($setting)
                : $setting;

            $model->update([
                'value'      => str_replace('{value}', $pure_value, $model->replace_template),
                'pure_value' => $pure_value
            ]);
        }
    }
}
