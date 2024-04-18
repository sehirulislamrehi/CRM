<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('DELETE FROM channels');
        DB::table('channels')->insert([
           [
               'id'=>1,
               'name'=>'CS-CART',
               'logo'=>'',
               'channel_number'=>'123456',
               'channel_type'=>'Common',
               'is_active'=>true,
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now(),
           ]
        ]);
    }
}
