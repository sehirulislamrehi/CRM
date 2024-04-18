<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('DELETE FROM users');
        DB::table('users')->insert([
           [
               'id'=>1,
               'fullname'=>'Tushar',
               'username'=>'tushar@123',
               'phone'=>'01416255256',
               'phone_login'=>'',
               'phone_password'=>'',
               'email'=>'',
               'user_group_id'=>2,
               'password'=>Hash::make('123456'),
               'role_id'=>1,
               'is_active'=>true,
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now()
           ]
        ]);
    }
}
