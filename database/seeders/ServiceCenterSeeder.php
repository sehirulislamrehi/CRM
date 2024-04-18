<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('DELETE FROM service_centers');
        DB::table('service_centers')->insert([
            [
                'id' => 1,
                'name' => 'Bonani Service Center',
                'address'=>"Bonani, Dhaka",
                'phone'=>'01316422582',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Motijheel Service Center',
                'address'=>"Motijheel Dhaka",
                'phone'=>'01316422584',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
        DB::statement('TRUNCATE table service_center_thana');
        DB::table('service_center_thana')->insert([
            [
                'service_center_id' => 1,
                'thana_id' => 1,
            ],
            [
                'service_center_id' => 1,
                'thana_id' => 2,
            ],
            [
                'service_center_id' => 1,
                'thana_id' => 3,
            ],
            [
                'service_center_id' => 2,
                'thana_id' => 2,
            ],
            [
                'service_center_id' => 2,
                'thana_id' => 3,
            ],
            [
                'service_center_id' => 2,
                'thana_id' => 4,
            ],
        ]);
    }
}
