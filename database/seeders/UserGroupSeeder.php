<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Don't change the id of critical marked code segment. that can lead to unwanted behavior of the application
     */
    public function run(): void
    {
        DB::statement("DELETE FROM user_groups");
        DB::table('user_groups')->insert([
            [
                'id' => 1,
                'name' => 'ADMIN',
                'group' => 'iContact ADMINISTRATION',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'name' => 'Agent',
                'group' => 'Agent',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            //Critical
            [
                'id' => 8,
                'name' => 'Service Center',
                'group' => 'Service Center',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 9,
                'name' => 'Technician',
                'group' => 'Technician',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            //End critical
            //Continue form here
        ]);
    }
}
