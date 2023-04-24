<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Roles_Permissions\PermissionSeeder;
use Database\Seeders\Roles_Permissions\RoleSeeder;
use Database\Seeders\Variable\VariableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(VariableSeeder::class);
    }
}
