<?php

namespace App\Http\Controllers\Backend\CommonModule\BusinessUnit;

use App\Http\Controllers\Controller;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\UserModule\User;
use App\Services\Backend\DatatableServices\CommonModule\BusinessUnit\BusinessUnitDatatableService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BusinessUnitController extends Controller
{
    protected BusinessUnitDatatableService $businessUnitDatatableService;

    /**
     * @param BusinessUnitDatatableService $businessUnitDatatableService
     */
    public function __construct(BusinessUnitDatatableService $businessUnitDatatableService)
    {
        $this->businessUnitDatatableService = $businessUnitDatatableService;
    }

    function index()
    {
        if (can('bu_index')) {
            return view("backend.modules.common_module.business_unit.index");
        }
        return view('error.403');
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            "name" => "required",
            "is_active" => "required"
        ]);
    }

    function data(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        if (can('bu_index')) {
            if (auth("web")->check()) {
                $userId = auth("web")->user()->id;
                $user = User::find($userId);
                if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                    $data=BusinessUnit::all();
                } else {
                    $data = User::with("business_unit")->find($userId)->business_unit;
                }
            } else {
                $data = BusinessUnit::query();
            }
            return $this->businessUnitDatatableService->getBusinessUnitData($data);
        }
        return view('error.403');
    }

    function create(Request $request)
    {
        // Validation
        if (can('bu_index')) {
            $this->validateRequest($request);

            try {
                $bu = new BusinessUnit();
                $bu->name = trim($request->input("name"));
                $bu->is_active = $request->input("is_active");

                if ($bu->save()) {
                    $alert = array(
                        'message' => 'Successfully Done',
                        'alertType' => 'success'
                    );
                    return response()->json($alert);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
            $alert = array(
                'message' => 'Not saved',
                'alertType' => 'error'
            );
            return response()->json($alert);

        }
        return response()->json([
            'message' => 'Unauthorized',
            'alertType' => 'warning'
        ]);
    }

    function update_modal($id)
    {
        if (can('bu_index')) {
            $bu = BusinessUnit::find($id);
            if ($bu) {
                return view("backend.modules.common_module.business_unit.modals.edit", compact("bu"));
            }
        }
        return view('error.modals.403');
    }

    function create_modal()
    {
        if (can('bu_index')) {
            return view("backend.modules.common_module.business_unit.modals.create");
        }
        return view('error.modals.403');
    }

    function update(Request $request, $id)
    {
        if (can('bu_index')) {
            $bu = BusinessUnit::find($id);

            if ($bu) {
                // Validation
                $this->validateRequest($request);
                try {
                    $bu->name = $request->input("name");
                    $bu->is_active = $request->input("is_active");
                    if ($bu->save()) {
                        $alert = array(
                            'message' => 'Successfully updated',
                            'alertType' => 'success'
                        );
                        return response()->json($alert);

                    }
                } catch (\Throwable $th) {
                    $alert = array(
                        'message' => 'Something went wrong',
                        'alertType' => 'error'
                    );
                    return response()->json($alert);

                }
            }
        }
        return response()->json([
            'message' => 'Unauthorized',
            'alertType' => 'warning'
        ]);

    }
}
