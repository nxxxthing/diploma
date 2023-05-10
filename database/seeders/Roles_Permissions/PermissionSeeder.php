<?php

declare(strict_types=1);

namespace Database\Seeders\Roles_Permissions;

use App\Api\v1\Enums\UserRoles;
use App\Facades\Permissions;
use App\Models\Role;
use Database\Seeders\BaseSeeder;

class PermissionSeeder extends BaseSeeder
{
    public function run()
    {
        $admin = Role::whereSlug(UserRoles::ADMIN->value)->first();

        Permissions::for($admin)->clearPermissions()->clear();


        Permissions::createModule('permissions', [
                'en' => 'Permissions',
                'ua' => 'Права',
            ])
            ->for($admin)
            ->syncModule()
            ->clear();

        Permissions::createModule('users', [
                'en' => 'User',
                'ua' => 'Користувачі',
            ])
            ->for($admin)
            ->syncModule()
            ->clear();

        Permissions::createModule('roles', [
                'en' => 'Roles',
                'ua' => 'Ролі'
            ])
            ->for($admin)
            ->syncModule()
            ->clear();

        Permissions::createModule('translations', [
                'en' => 'Translations',
                'ua' => 'Переклади',
            ])
            ->for($admin)
            ->except(['create', 'edit', 'delete', 'show'])
            ->syncModule()
            ->clear();

        Permissions::createModule('site_management', [
                'en' => 'Site management',
                'ua' => 'Управління сайтом',
            ])
            ->for($admin)
            ->except(['create', 'edit', 'delete', 'show'])
            ->syncModule()
            ->clear();

        Permissions::createModule('admin_settings', [
                'en' => 'Admin settings',
                'ua' => 'Налаштування адміністратора',
            ])
            ->for($admin)
            ->except(['create', 'edit', 'delete', 'show'])
            ->syncModule()
            ->clear();

        Permissions::createModule(UserRoles::ADMIN->value, [
                'en' => 'Admin',
                'ua' => 'Адмін',
            ])
            ->for($admin)
            ->except(['create', 'edit', 'delete', 'show'])
            ->syncModule()
            ->clear();

        Permissions::createModule(UserRoles::STUDENT->value, [
                'en' => 'Student',
                'ua' => 'Студент',
            ])
            ->for($admin)
            ->except(['create', 'edit', 'delete', 'show'])
            ->syncModule()
            ->clear();

        Permissions::createModule(UserRoles::TEACHER->value, [
                'en' => 'Teacher',
                'ua' => 'Викладач',
            ])
            ->for($admin)
            ->except(['create', 'edit', 'delete', 'show'])
            ->syncModule()
            ->clear();


        Permissions::createModule('modules', [
                'en' => 'Criteria',
                'ua' => 'Критерії',
            ])
            ->for($admin)
            ->syncModule()
            ->clear();

        Permissions::createModule('variables', [
            'en' => 'Variables',
            'ua' => 'Змінні',
        ])
            ->for($admin)
            ->syncModule()
            ->clear();

        Permissions::createModule('faculties', [
            'en' => 'Faculties',
            'ua' => 'Факультети',
        ])
            ->for($admin)
            ->syncModule()
            ->clear();

        Permissions::createModule('cathedras', [
            'en' => 'Cathedra',
            'ua' => 'Кафедра',
        ])
            ->for($admin)
            ->syncModule()
            ->clear();

        Permissions::createModule('groups', [
            'en' => 'Groups',
            'ua' => 'Групи',
        ])
            ->for($admin)
            ->syncModule()
            ->clear();

        Permissions::createModule('lessons', [
            'en' => 'Lessons',
            'ua' => 'Предмети',
        ])
            ->for($admin)
            ->syncModule()
            ->clear();
    }
}
