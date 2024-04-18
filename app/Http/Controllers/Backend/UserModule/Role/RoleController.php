<?php

namespace App\Http\Controllers\Backend\UserModule\Role;

use App\Http\Controllers\Controller;
use App\Models\Backend\UserModule\Module;
use App\Models\Backend\UserModule\Role;
use App\Services\Backend\DatatableServices\UserModule\Role\RoleDatatableService;
use App\Services\Backend\ModuleServices\UserModule\Role\RoleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    protected RoleDatatableService $roleDatatableService;
    protected RoleService $roleService;

    /**
     * @param RoleDatatableService $roleDatatableService
     * @param RoleService $roleService
     */
    public function __construct(RoleDatatableService $roleDatatableService, RoleService $roleService)
    {
        $this->roleDatatableService = $roleDatatableService;
        $this->roleService = $roleService;
    }


    function index()
    {
        return view('backend.modules.user_module.role.index');
    }

    public function data()
    {
        if (can('role_index')) {
            $role = Role::select("id", "name", "is_active")->get();
            return $this->roleDatatableService->getAllRole($role);
        } else {
            return unauthorized();
        }
    }

    public function create(Request $request)
    {
        if (can('add_roles')) {
            $request->validate([
                'name' => 'required',
                'is_active' => 'required',
            ]);
            try {
                if ($request['permission']) {
                    $this->roleService->createRole($request);
                    $alert = array(
                        'message' => 'Successfully done',
                        'alertType' => 'success'
                    );
                } else {
                    $alert = array(
                        'message' => 'Permission is missing',
                        'alertType' => 'warning'
                    );
                }
                return response()->json($alert);
            } catch (Exception $e) {
                $alert = array(
                    'message' => $e->getMessage(),
                    'alertType' => 'error'
                );
                return response()->json($alert);
            }
        } else {
            $alert = array(
                'message' => 'unauthorized',
                'alertType' => 'error'
            );
            return response()->json($alert);
        }
    }


    public function edit_modal(Request $request, $id)
    {
        if (can('edit_roles')) {
            $role = $this->roleService->getRoleById($id);
            $modules = $this->roleService->getModule();
            return view("backend.modules.user_module.role.modals.edit", compact('role', 'modules'));
        } else {
            return unauthorized();
        }
    }

    public function create_modal()
    {
        if (can('edit_roles')) {
            $modules = Module::orderBy("position", "asc")->select("id", "name", "key")->with("permission")->get();
            return view("backend.modules.user_module.role.modals.create",compact('modules'));
        } else {
            return unauthorized();
        }
    }

    public function update(Request $request, $id)
    {
        if (can('edit_roles')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'is_active' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                try {
                    if ($request['permission']) {
                        if ($this->roleService->updateRole($request, $id)) {
                            $alert = array(
                                'message' => 'Successfully updated',
                                'alertType' => 'success'
                            );
                            return response()->json($alert);
                        }
                    } else {
                        $alert = array(
                            'message' => 'Permission missing',
                            'alertType' => 'warning'
                        );
                        return response()->json($alert);
                    }
                } catch (Exception $e) {
                    $alert = array(
                        'message' => 'Failed to update',
                        'alert-type' => 'error'
                    );
                    return response()->json($alert);
                }
            }
        } else {
            return response()->json(['status' => 'warning', 'warning' => unauthorized()], 200);
        }
        return response()->json(['status' => 'warning', 'warning' => unauthorized()], 200);

    }
}
