<?php

declare(strict_types=1);

use App\Models\AdminSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateAdminSettingsTable extends Migration
{

    public function up()
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->nullable();
            $table->string('key');
            $table->text('value')->nullable();
            $table->text('pure_value')->nullable();
            $table->text('replace_template')->nullable();
            $table->text('default')->nullable();
            $table->text('default_color')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        try {
            $path = 'public/favicons/';
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);

                File::copy(
                    base_path('resources/img/admin_settings/favicon.ico'),
                    base_path('public/favicons/favicon.ico')
                );
            }
        } catch (Exception $e) {
            Log::error(['move icon error' => $e->getMessage()]);
        }


        $data = [
            //      Title
            [
                'group'            => 'admin_title',
                'key'              => 'title',
                'value'            => 'Admin',
                'pure_value'       => 'Admin',
                'replace_template' => '{value}',
                'default'          => 'Admin',
                'default_color'    => 'Admin'
            ],
            [
                'group'            => 'admin_title',
                'key'              => 'title_prefix',
                'value'            => 'LTE',
                'pure_value'       => 'LTE',
                'replace_template' => '{value}',
                'default'          => 'LTE',
                'default_color'    => 'LTE'
            ],
            [
                'group'            => 'admin_title',
                'key'              => 'title_postfix',
                'value'            => '3',
                'pure_value'       => '3',
                'replace_template' => '{value}',
                'default'          => '3',
                'default_color'    => '3'
            ],
            [
                'group'            => 'admin_title',
                'key'              => 'favicon',
                'value'            => 'public/favicons/favicon.ico',   //put file to public/favicons/favicon.ico
                'pure_value'       => 'public/favicons/favicon.ico',
                'replace_template' => '{value}',
                'default'          => 'public/favicons/favicon.ico',
                'default_color'    => 'public/favicons/favicon.ico'
            ],

            //      Logo

            [
                'group'            => 'logo',
                'key'              => 'logo',
                'value'            => '<b>Admin</b>LTE',
                'pure_value'       => '<b>Admin</b>LTE',
                'replace_template' => '{value}',
                'default'          => '<b>Admin</b>LTE',
                'default_color'    => '<b>Admin</b>LTE'
            ],
            [
                'group'            => 'logo',
                'key'              => 'logo_img',
                'value'            => config('adminlte.logo_img'),  //small logo image 50x50px
                'pure_value'       => config('adminlte.logo_img'),
                'replace_template' => '{value}',
                'default'          => config('adminlte.logo_img'),
                'default_color'    => config('adminlte.logo_img')
            ],
            [
                'group'            => 'logo',
                'key'              => 'logo_img_xl',
                'status'           => false,
                'value'            => null,      //not required, big logo image 210x33px
                'pure_value'       => null,
                'replace_template' => '{value}',
                'default'          => null,
                'default_color'    => null
            ],

            //          User menu

            [
                'group'            => 'usermenu',
                'key'              => 'usermenu_enabled',
                'value'            => true,
                'pure_value'       => true,
                'replace_template' => '{value}',
                'default'          => true,
                'default_color'    => true
            ],
            [
                'group'            => 'usermenu',
                'key'              => 'usermenu_header',
                'value'            => true,
                'pure_value'       => true,
                'replace_template' => '{value}',
                'default'          => true,
                'default_color'    => true
            ],
            [
                'group'            => 'usermenu',
                'key'              => 'usermenu_header_class',
                'value'            => 'bg-gray-dark',
                'pure_value'       => 'gray-dark',
                'replace_template' => 'bg-{value}',
                'default'          => 'bg-gray-dark',
                'default_color'    => 'gray-dark'
            ],

            //          Side bars

            [
                'group'            => 'side_bars_left_side_navbar',
                'key'              => 'classes_sidebar',
                'value'            => 'navbar-gray-dark',                  //navbar-{color}
                'pure_value'       => 'gray-dark',
                'replace_template' => 'navbar-{value}',
                'default'          => 'navbar-gray-dark',
                'default_color'    => 'gray-dark'
            ],
            [
                'group'            => 'side_bars_left_side_navbar_text',
                'key'              => 'classes_sidebar',
                'value'            => 'sidebar-dark',                      //dark || light
                'pure_value'       => 'dark',
                'replace_template' => 'sidebar-{value}',
                'default'          => 'sidebar-dark',
                'default_color'    => 'dark'
            ],
            [
                'group'            => 'side_bars_left_side_navbar_text_hover',
                'key'              => 'classes_sidebar',
                'value'            => 'gray-dark elevation-4',
                'pure_value'       => 'gray-dark',
                'replace_template' => '{value} elevation-4',
                'default'          => 'gray-dark elevation-4',
                'default_color'    => 'gray-dark'
            ],

            [
                'group'            => 'side_bars_top_sidebar',
                'key'              => 'classes_topnav',
                'value'            => 'navbar-gray-dark',
                'pure_value'       => 'gray-dark',
                'replace_template' => 'navbar-{value}',
                'default'          => 'navbar-gray-dark',
                'default_color'    => 'gray-dark'
            ],
            [
                'group'            => 'side_bars_top_text',
                'key'              => 'classes_topnav',
                'value'            => 'navbar-dark',                       //dark || light
                'pure_value'       => 'dark',
                'replace_template' => 'navbar-{value}',
                'default'          => 'navbar-dark',
                'default_color'    => 'dark'
            ],
        ];

        foreach ($data as $item) {
            AdminSetting::create($item);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings');
    }
}
