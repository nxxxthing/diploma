<?php

declare(strict_types=1);

namespace App\Classes;

use App\Http\Middleware\LocaleMiddleware;
use App\Models\Permission;
use App\Models\Role;

class Permissions
{
    private array $except;

    private array $role;

    private array $module;

    private array $permissions = [
        'access' => [
            'en' => 'access',
            'ua' => 'доступ',
        ],
        'create' => [
            'en' => 'create',
            'ua' => 'створення',
        ],
        'edit' => [
            'en' => 'edit',
            'ua' => 'редагування',
        ],
        'delete' => [
            'en' => 'delete',
            'ua' => 'видалення',
        ],
        'show' => [
            'en' => 'show',
            'ua' => 'перегляд',
        ],
    ];

    public function for(Role ...$role): static
    {
        $this->role = $role;
        return $this;
    }

    public function except(array $except): static
    {
        $this->except = $except;
        return $this;
    }

    public function createModule($slug, $translations): static
    {
        $this->checkExist('permissions');

        foreach ($this->permissions as $permission => $permissionTranslations) {
            $locales = [];
            foreach (LocaleMiddleware::$languages as $locale) {
                if (isset($translations[$locale]) && isset($permissionTranslations[$locale])) {
                    $locales[$locale] = [
                        'title' => $translations[$locale] . ' ' . $permissionTranslations[$locale]
                    ];
                }
            }
            $this->module[] = Permission::firstOrCreate(
                [
                    'slug' => $slug . '_' . $permission
                ],
                $locales
            );
        }

        return $this;
    }

    public function syncModule(): static
    {
        $this->checkExist('module', 'role');
        $sync = [];
        foreach ($this->module as $permission) {
            $accessor = substr($permission->slug, strrpos($permission->slug, '_') + 1);

            if (!in_array($accessor, $this->except)) {
                $sync[] = $permission->id;
            }
        }

        foreach ($this->role as $role) {
            $role->permissions()->syncWithoutDetaching($sync);
        }

        return $this;
    }

    public function clear(): static
    {
        $this->except = [];
        $this->role = [];
        $this->module = [];

        return $this;
    }

    public function clearPermissions(): static
    {
        $this->checkExist('role');
        foreach ($this->role as $role) {
            $role->permissions()->sync([]);
        }

        return $this;
    }

    public function withAdditional($permissions): static
    {
        foreach ($permissions as $key => $permission) {
            $this->permissions[$key] = $permission;
        }

        return $this;
    }

    private function checkExist(string ...$parameters)
    {
        foreach ($parameters as $parameter) {
            if (!isset($this->{$parameter}) || ! $this->{$parameter}) {
                dump("$parameter is not set");
            }
        }
    }
}
