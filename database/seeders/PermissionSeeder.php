<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement("DELETE FROM permissions");

        DB::table('permissions')->insert([
            //Common module
            [
                'id' => 1,
                'key' => 'common_module',
                'display_name' => 'Common Module',
                'module_id' => 1,
            ],
            [
                'id' => 2,
                'key' => 'bu_index',
                'display_name' => 'Business Units',
                'module_id' => 1,
            ],
            [
                'id' => 3,
                'key' => 'district_index',
                'display_name' => 'District',
                'module_id' => 1,
            ],
            [
                'id' => 4,
                'key' => 'service_center_index',
                'display_name' => 'Service Center',
                'module_id' => 1,
            ],
            [
                'id' => 5,
                'key' => 'thana_index',
                'display_name' => 'Thana',
                'module_id' => 1,
            ],
            [
                'id' => 6,
                'key' => 'brand_index',
                'display_name' => 'Brand',
                'module_id' => 1,
            ],
            //User model
            [
                'id' => 7,
                'key' => 'user_module',
                'display_name' => 'User Module',
                'module_id' => 2,
            ],
            [
                'id' => 8,
                'key' => 'user_index',
                'display_name' => 'User',
                'module_id' => 2,
            ],
            [
                'id' => 9,
                'key' => 'role_index',
                'display_name' => 'Role',
                'module_id' => 2,
            ],
            [
                'id' => 10,
                'key' => 'channel_index',
                'display_name' => 'Channel',
                'module_id' => 2,
            ],
            //Product Module
            [
                'id' => 11,
                'key' => 'product_module',
                'display_name' => 'Product',
                'module_id' => 3
            ],
            [
                'id' => 12,
                'key' => 'product_category_index',
                'display_name' => 'Product Category',
                'module_id' => 3
            ],
            //Ticketing module
            [
                'id' => 13,
                'key' => 'ticket_module',
                'display_name' => 'Ticket Module',
                'module_id' => 4,
            ],
            [
                'id' => 14,
                'key' => 'ticket_index',
                'display_name' => 'Ticket',
                'module_id' => 4
            ],
            [
                'id' => 15,
                'key' => 'agent_panel',
                'display_name' => 'Agent Panel',
                'module_id' => 4
            ],

        ]);
    }
}
