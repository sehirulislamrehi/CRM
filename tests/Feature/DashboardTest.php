<?php

namespace Tests\Feature;

use App\Models\Backend\UserModule\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function check_if_super_admin_can_access_the_database(): void
    {
        $user=SuperAdmin::first();
        if($user){
            Auth::guard('super_admin')->login($user);
            $response = $this->get(route('admin.dashboard.index'));
            $response->assertStatus(200);
        }
        else{
            self::fail("User not found on the database");
        }

    }
}
