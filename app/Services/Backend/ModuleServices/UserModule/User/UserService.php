<?php

namespace App\Services\Backend\ModuleServices\UserModule\User;

use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\CommonModule\ServiceCenter;
use App\Models\Backend\UserModule\Role;
use App\Models\Backend\UserModule\User;
use App\Models\Backend\UserModule\UserGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class UserService
{

    public function getUserGroups()
    {
        if (auth('super_admin')->check()) {
            return $user_groups = UserGroup::where('is_active', true)->get();
        }
        return UserGroup::where('is_active', true)
            ->whereNotIn('name', ['Admin', 'Agent'])
            ->get();

    }

    public function getRoles()
    {
        if (auth('super_admin')->check()) {
            return Role::where('is_active', true)->get();
        }
        return Role::whereNotIn('name', ['Admin', 'Agent'])->where('is_active', true)->get();
    }

    public function getBusinessUnit()
    {
        if (auth("web")->check()) {
            $userId = auth("web")->user()->id;
            $user = User::find($userId);
            if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                $data = BusinessUnit::where("is_active", true)->get();

            } else {
                $data = User::with("business_unit")->find($userId)->business_unit;
            }

        } else {
            $data = BusinessUnit::where("is_active", true)->get();
        }
        return $data;
    }

    public function getServiceCenters()
    {
        if (auth('super_admin')->check()) {
            return $service_centers = ServiceCenter::where('is_active', true)->get();
        }
        $user = auth('web')->user();
        $userServiceCenter = DB::table('service_center_user')->where('user_id', $user->id)->pluck('service_center_id')->toArray();
        return ServiceCenter::where('is_active', true)->whereIn('id', $userServiceCenter)->get();

    }

    public function getAllUser()
    {
        if (auth('super_admin')->check()) {
            return User::orderBy('id', 'DESC');
        } else {
            $userId = auth('web')->user()->id;
            $user = User::find($userId);
            if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                return User::whereNot("id", $user->id)->orderBy('id', 'DESC');
            } elseif ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) > 0) {
                $userBusinessUnit = DB::table("business_unit_user")->where("user_id", $user->id)->pluck("business_unit_id")->toArray();
                $userIds = DB::table("business_unit_user")->whereIn("business_unit_id", $userBusinessUnit)->pluck("user_id")->toArray();
                return User::whereIn('id', $userIds)->whereNot("user_group_id", 1)->orderBy('id', 'DESC');
            } else {
                $userBusinessUnit = DB::table("business_unit_user")->where("user_id", $user->id)->pluck("business_unit_id")->toArray();
                $userIds = DB::table("business_unit_user")->whereIn("business_unit_id", $userBusinessUnit)->pluck("user_id")->toArray();
                return User::whereIn('id', $userIds)->where("user_group_id", 9)->orderBy('id', 'DESC');
            }
        }
    }

    public function userSelectedServiceCenter(User $user): array
    {
        return $user->service_center()->pluck('id')->toArray();
    }

    public function userSelectedBusinessUnit($user)
    {
        return $user->business_unit()->pluck('id')->toArray();

    }

    public function getUserDetails($id)
    {
        return User::find($id);
    }

    public function updateUser($request, User $user)
    {
        $user->fullname = $request->input('fullname');
        $user->username = $request->input('username');
        $user->phone = $request->input('phone');
        // $user->password = Hash::make($request->input('password'));
        $user->phone_login = $request->input('phone_login');
        $user->phone_password = $request->input('phone_password');
        $user->user_group_id = $request->input('user_group_id');
        $user->role_id = $request->input('role_id');
        $user->is_active = $request->input('is_active');
        if ($user->save()) {
            if ($request->input('user_group_id') == 8 || $request->input('user_group_id') == 9 || $request->input('user_group_id') == 1) {
                $serviceCenterId = $request->input('service_center_id') ?? [];
                if (count($serviceCenterId) > 0) {
                    $this->handelServiceCenterRelationship($user, $serviceCenterId);
                // }
                $businessUnitId = $request->input('business_unit_id');
                if (count($businessUnitId) > 0) {
                    $this->handelBusinessUnitRelationship($user, $businessUnitId);
                }
                $businessUnitId = $request->input('business_unit_id') ?? [];
                $this->handelBusinessUnitRelationship($user, $businessUnitId);
            }
            return true;
        }
        return false;
    }

    public function createUser($request)
    {
        if (auth('web')->check()) {
            if (array_diff($request['service_center_id'], $this->getCurrentlyAssignedServiceCenterForWebGuard())) {
                return false;
            }
        }
        $user = new User();
        $user->fullname = $request->input('fullname');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->phone_login = $request->input('phone_login');
        $user->phone_password = $request->input('phone_password');
        $user->user_group_id = $request->input('user_group_id');
        $user->role_id = $request->input('role_id');
        $user->is_active = $request->input('is_active');
        if ($user->save()) {
            if ($request->input('user_group_id') == 8 || $request->input('user_group_id') == 9 || $request->input('user_group_id') == 1) {
                $serviceCenterId = $request->input('service_center_id') ?? [];
                $this->handelServiceCenterRelationship($user, $serviceCenterId);
                $businessUnitId = $request->input('business_unit_id') ?? [];
                $this->handelBusinessUnitRelationship($user, $businessUnitId);

            }
            return true;
        }
        return false;

    }

    private function handelServiceCenterRelationship($user, $serviceCenterId): void
    {
        $user->service_center()->sync($serviceCenterId);
    }

    private function handelBusinessUnitRelationship($user, $businessUnitId): void
    {
        $user->business_unit()->sync($businessUnitId);
    }

    private function getCurrentlyAssignedServiceCenterForWebGuard(): array
    {
        $user = auth('web')->user();
        return DB::table('service_center_user')->where('user_id', $user->id)->pluck('service_center_id')->toArray();

    }


}
