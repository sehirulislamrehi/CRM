<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("DELETE FROM modules");
        DB::table('modules')->insert([
            [
                'id' => 1,
                'name' => 'Common Module',
                'key' => 'common_module',
                'icon' => 'fas fa-users',
                'position' => 1,
                'route' => null
            ],
            [
                'id' => 2,
                'name' => 'User Module',
                'key' => 'user_module',
                'icon' => 'fas fa-users',
                'position' => 2,
                'route' => null
            ],
            [
                'id' => 3,
                'name' => 'Product Module',
                'key' => 'product_module',
                'icon' => 'fas fa-boxes',
                'position' => 3,
                'route' => null
            ],
            [
                'id' => 4,
                'name' => 'Ticket Module',
                'key' => 'ticket_module',
                'icon' => 'fas fa-ticket-alt',
                'position' => 4,
                'route' => null
            ]

        ]);
    }
}
