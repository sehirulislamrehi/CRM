<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('DELETE FROM business_units');
        DB::table('business_units')->insert([
            [
                'id'=>1,
                'name' => 'BBML-Door',
                'is_active'=>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'=>2,
                'name' => 'BBML-F',
                'is_active'=>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'=>3,
                'name' => 'RMIL',
                'is_active'=>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'=>4,
                'name' => 'RPLE',
                'is_active'=>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'=>5,
                'name' => 'RAC',
                'is_active'=>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'=>6,
                'name' => 'Tasty Treat',
                'is_active'=>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'=>7,
                'name' => 'BBL- Mithai',
                'is_active'=>true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
