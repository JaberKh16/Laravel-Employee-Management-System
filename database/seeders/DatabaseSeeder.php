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
        // \App\Models\User::factory(10)->create();
        $this->call([
                // 1. Roles/permissions first (Spatie)
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,

                // 2. Locations
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            DepartmentSeeder::class,


                // 3. Users — need to exist before assigning managers
            EmployeeSeeder::class,

                // UserSeeder::class,          // if you have one
                // ProfileSeeder::class,       // ← add this


                // 4. Departments — depend on users
            DepartmentSeeder::class,
        ]);
    }
}
