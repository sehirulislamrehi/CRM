<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement("DELETE FROM sub_modules");
        DB::table('sub_modules')->insert([
            //module id 1 start
            [
                'id' => 1,
                'name' => 'Business units',
                'key' => 'bu_index',
                'position' => 1,
                'route' => 'admin.common-module.bu.index',
                'module_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'District',
                'key' => 'district_index',
                'position' => 2,
                'route' => 'admin.common-module.district.index',
                'module_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Thana',
                'key' => 'thana_index',
                'position' => 3,
                'route' => 'admin.common-module.thana.index',
                'module_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Service Center',
                'key' => 'service_center_index',
                'position' => 4,
                'route' => 'admin.common-module.service-center.index',
                'module_id' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Brand',
                'key' => 'brand_index',
                'position' => 5,
                'route' => 'admin.common-module.brand.index',
                'module_id' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Channel',
                'key' => 'channel_index',
                'position' => 5,
                'route' => 'admin.common-module.channel.index',
                'module_id' => 1,
            ],
            //User module start
            [
                'id' => 7,
                'name' => 'User',
                'key' => 'user_index',
                'position' => 1,
                'route' => 'admin.user-module.user.index',
                'module_id' => 2,
            ],
            [
                'id' => 8,
                'name' => 'Role',
                'key' => 'role_index',
                'position' => 1,
                'route' => 'admin.user-module.role.index',
                'module_id' => 2,
            ],
            //Product module start
            [
                'id' => 9,
                'name' => 'Product Category',
                'key' => 'product_category_index',
                'position' => 1,
                'route' => 'admin.product-module.product-category.index',
                'module_id' => 3
            ],
            //Ticketing Module
            [
                'id' => 10,
                'name' => 'Ticketing',
                'key' => 'ticket_index',
                'position' => 1,
                'route' => 'ticket.admin.ticket.index',
                'module_id' => 4
            ]
        ]);
    }
}
