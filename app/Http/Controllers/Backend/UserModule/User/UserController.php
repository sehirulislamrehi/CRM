<?php

namespace App\Http\Controllers\Backend\UserModule\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserModule\User\UserCreateRequest;
use App\Http\Requests\Backend\UserModule\User\UserUpdateRequest;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\UserModule\User;
use App\Services\Backend\DatatableServices\UserModule\User\UserDatatableService;
use App\Services\Backend\ModuleServices\UserModule\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    protected UserService $userService;
    protected UserDatatableService $userDatatableService;

    /**
     * @param UserService $userService
     * @param UserDatatableService $userDatatableService
     */
    public function __construct(UserService $userService, UserDatatableService $userDatatableService)
    {
        $this->userService = $userService;
        $this->userDatatableService = $userDatatableService;
    }


    function index(): View
    {
        if (can('user_index')) {
            return view('backend.modules.user_module.user.index');
        }
        return view('error.403');
    }


    function data(): \Illuminate\Http\JsonResponse
    {
        if (can('user_index')) {
            $data = $this->userService->getAllUser();
            return $this->userDatatableService->getUserData($data);
        }
        $alert = array(
            'message' => 'Unauthorized',
            'alertType' => 'error'
        );
        return response()->json($alert);
    }

    function create(UserCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        if (can('user_index')) {
            $request->validated();
            try {
                $this->userService->createUser($request);
                $alert = array(
                    'message' => 'Successfully saved',
                    'alertType' => 'success'
                );
                return response()->json($alert);

            } catch (\Throwable $th) {
                $alert = array(
                    'message' => $th->getMessage(),
                    'alertType' => 'error'
                );
                return response()->json($alert);

            }
        }
        $alert = array(
            'message' => 'Unauthorized',
            'alertType' => 'error'
        );
        return response()->json($alert);
    }

    public function edit($id): View
    {
        if (can('user_index')) {
            $user = $this->userService->getUserDetails($id);
            $groups = $this->userService->getUserGroups();
            $roles = $this->userService->getRoles();
            $service_centers = $this->userService->getServiceCenters();
            $selected_service_center = $this->userService->userSelectedServiceCenter($user);
            $selected_business_unit=$this->userService->userSelectedBusinessUnit($user);
            $business_units=$this->userService->getBusinessUnit();
            return view('backend.modules.user_module.user.modals.edit', compact('user', 'groups', 'roles', 'service_centers', 'selected_service_center','selected_business_unit','business_units'));
        }
        return view('error.modals.403');
    }


    public function update(UserUpdateRequest $request,int $id): \Illuminate\Http\JsonResponse
    {
        if (can('user_index')) {
            $user = User::findOrFail($id);
            $request->validated();
            try {
                $this->userService->updateUser($request, $user);
                $alert = array(
                    'message' => 'Successfully Done',
                    'alertType' => 'success'
                );
                return response()->json($alert);

            } catch (\Throwable $th) {
                $alert = array(
                    'message' => $th->getMessage(),
                    'alertType' => 'error'
                );
                return response()->json($alert);
            }

        }
        $alert = array(
            'message' => 'Unauthorized',
            'alertType' => 'error'
        );
        return response()->json($alert);
    }

    public function create_modal(): View
    {
        if (can('user_index')) {
            $user_groups = $this->userService->getUserGroups();
            $roles = $this->userService->getRoles();
            $service_centers = $this->userService->getServiceCenters();
            $business_units=$this->userService->getBusinessUnit();
            return view('backend.modules.user_module.user.modals.create', compact('user_groups', 'roles', 'service_centers','business_units'));
        }
        return view('error.modals.403');
    }


    public function getServiceCenterByBu(Request $request): \Illuminate\Http\JsonResponse
    {
        $selectedBu=$request->input("selectedBu");
        $data=DB::table('service_centers')
            ->join("business_unit_service_center",'service_centers.id',"=",'business_unit_service_center.service_center_id')
            ->join('business_units',"business_units.id","=","business_unit_service_center.business_unit_id")
            ->select('service_centers.id','service_centers.name')
            ->whereIn("business_units.id",$selectedBu)
            ->get();
        return response()->json($data);
    }
}
