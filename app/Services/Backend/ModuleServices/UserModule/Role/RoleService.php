<?php

namespace App\Services\Backend\ModuleServices\UserModule\Role;

use App\Models\Backend\UserModule\Module;
use App\Models\Backend\UserModule\Role;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class RoleService
{
    public function getRoleById($id)
    {
        return Role::where("id", $id)->first();
    }

    public function getModule()
    {
        return Module::orderBy("position", "asc")->select("id", "name", "key")->with("permission")->get();
    }

    public function createRole($request): bool
    {
        $role = new Role();
        $role->name = $request->name;
        $role->is_active = true;
        if ($role->save()) {
            $permission = $request['permission'];
            $this->handlePermissionRelationship($role, $permission);
            return true;
        }
        return false;
    }

    public function updateRole($request, $id): bool
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->is_active = $request->is_active;
        if ($role->save()) {
            $permission = $request['permission'];
            $this->handlePermissionRelationship($role, $permission);
            return true;
        }
        return false;
    }

    private function handlePermissionRelationship($role, $permission): void
    {
        $role->permission()->sync($permission);
    }
}
