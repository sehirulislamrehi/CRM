<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("DELETE FROM thanas");

        $currentTimestamp = Carbon::now()->toDateTimeString();

        $thanas = [
            //Dhaka
            ["name" => "Motijheel", "district_id" => 1, "created_at" => $currentTimestamp, "updated_at" => $currentTimestamp],
            ["name" => "Rampura", "district_id" => 1, "created_at" => $currentTimestamp, "updated_at" => $currentTimestamp],
            ["name" => "Gulshan", "district_id" => 1, "created_at" => $currentTimestamp, "updated_at" => $currentTimestamp],
            ["name" => "Vatara", "district_id" => 1, "created_at" => $currentTimestamp, "updated_at" => $currentTimestamp],
            ["name" => "Khilkhet", "district_id" => 1, "created_at" => $currentTimestamp, "updated_at" => $currentTimestamp],
            ["name" => "Mirpur", "district_id" => 1, "created_at" => $currentTimestamp, "updated_at" => $currentTimestamp],
            ["name" => "Ramna", "district_id" => 1, "created_at" => $currentTimestamp, "updated_at" => $currentTimestamp],
            ["name" => "Khilgaon", "district_id" => 1, "created_at" => $currentTimestamp, "updated_at" => $currentTimestamp],
           
            
            
        ];

        DB::table('thanas')->insert($thanas);
    }
}
