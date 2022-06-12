<?php

namespace Database\Seeders;

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
        $this->call([
            // make sure to use the roles and permission seeder first, because we want to create the permission before create user
            RolesAndPermissionsSeeder::class,
            UserTableSeeder::class
        ]);
    }
}
