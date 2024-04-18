<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ModuleSeeder::class,
            SubModuleSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            BusinessUnitSeeder::class,
            ChannelSeeder::class,
            BrandSeeder::class,
            DistrictSeeder::class,
//            ThanaSeeder::class,
            SuperAdminSeeder::class,
            UserGroupSeeder::class,
            UserSeeder::class,
//            ServiceCenterSeeder::class
        ]);
    }
}
