<?php

namespace App\Http\Controllers\Backend\CommonModule\ServiceCenter;

use App\Http\Controllers\Controller;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\CommonModule\ServiceCenter;
use App\Models\Backend\CommonModule\Thana;
use App\Models\Backend\UserModule\User;
use App\Services\Backend\DatatableServices\CommonModule\ServiceCenter\ServiceCenterDatatableService;
use App\Services\Backend\ModuleServices\CommonModule\ServiceCenter\ServiceCenterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ServiceCenterController extends Controller
{
    protected ServiceCenterService $serviceCenterService;
    protected ServiceCenterDatatableService $serviceCenterDatatableService;

    /**
     * @param ServiceCenterService $serviceCenterService
     * @param ServiceCenterDatatableService $serviceCenterDatatableService
     */
    public function __construct(ServiceCenterService $serviceCenterService, ServiceCenterDatatableService $serviceCenterDatatableService)
    {
        $this->serviceCenterService = $serviceCenterService;
        $this->serviceCenterDatatableService = $serviceCenterDatatableService;
    }


    function index()
    {
        if (can('service_center_index')) {
            return view('backend.modules.common_module.service_center.index');
        }
        return view('error.403');
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            "name" => "required",
            'address' => 'required',
            "is_active" => "required",
            "business_unit_id"=>[
              "required",
              "array",
              Rule::exists("business_units","id")
            ],
            "thana_id" => [
                'sometimes',
                'required',
                'array',
                Rule::exists('thanas', 'id'),
                'min:1', // Ensure at least one element in the array
            ],
        ]);
    }

    /**
     * @throws Exception
     */
    function data()
    {
        if (can('service_center_index')) {
            if (auth("web")->check()) {
                $userId = auth("web")->user()->id;
                $user = User::find($userId);
                if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                    $data = ServiceCenter::orderByDesc("id");
                }
                else{
                    $service_center_ids = DB::table('business_unit_user as buu')
                        ->join('business_unit_service_center as bus', 'buu.business_unit_id', '=', 'bus.business_unit_id')
                        ->where('buu.user_id', $user->id)
                        ->pluck('bus.service_center_id')
                        ->toArray();
                    $data=ServiceCenter::whereIn("id",$service_center_ids)->orderByDesc("id");

                }
            } else {
                $data = ServiceCenter::orderByDesc("id");
            }
            return $this->serviceCenterDatatableService->getServiceCenterData($data);

        }
        return view('error.403');
    }

    function create_modal(Request $request)
    {
        if (can('service_center_index')) {
            $thana_list = Thana::where('is_active', true)->get();
            $business_units=$this->serviceCenterService->getBusinessUnit();
            return view('backend.modules.common_module.service_center.modals.create', compact('thana_list',"business_units"));
        }
        return view('error.modals.403');

    }

    function create(Request $request)
    {
        if (can('service_center_index')) {
            $this->validateRequest($request);
            try {
                $this->serviceCenterService->createServiceCenter($request);
                return response()->json([
                    'message' => 'Successfully Done',
                    'alertType' => 'success'
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'message' => $th->getMessage(),
                    'alertType' => 'error'
                ]);
            }
        }
        return view('error.403');
    }

    function details($id):View
    {
        if (can('service_center_index')) {
            $su = ServiceCenter::findOrFail($id);
            $all_thana_list = Thana::where('is_active', true)->get();
            $selected_thanas = $su->thana()->pluck('id')->toArray();
            $business_units=$this->serviceCenterService->getBusinessUnit();
            $selected_business_unit=$this->serviceCenterService->getSelectedBusinessUnit($id);
            return view("backend.modules.common_module.service_center.modals.edit", compact("su", "all_thana_list", "selected_thanas","business_units","selected_business_unit"));
        }
        return view('error.modals.403');

    }

    function update(Request $request, $id)
    {
        if (can('service_center_index')) {
            // Validation
            $this->validateRequest($request);
            try {
                $this->serviceCenterService->updateServiceCenter($request, $id);
                return response()->json([
                    'message' => 'Successfully Done',
                    'alertType' => 'success'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => $th->getMessage(),
                    'alertType' => 'error'
                ]);
            }
        }
        return view('error.403');
    }

    function bulk_upload_modal(): View
    {
        if (can('service_center_index')) {
            $bu = BusinessUnit::where('is_active', true)->get();
            return view('backend.modules.common_module.service_center.modals.bulk_upload', compact('bu'));
        }
        return view('error.modals.403');
    }

    function bulk_upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'bu_id' => 'required|exists:business_units,id',
            'file' => 'required'
        ], [
            'bu_id.required' => 'Business unit field is required',
        ]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'csv') {
                return response()->json([
                    'message' => 'Please upload a csv file',
                    'alertType' => 'error'
                ]);
            }
            $bu_id = $request->input('bu_id');
//            dd($this->serviceCenterService->uploadBulk($file,$bu_id));
            $returnedResponse = $this->serviceCenterService->uploadBulk($file, $bu_id);
            return response()->json(
                [
                    'message' => $returnedResponse['message'],
                    'alertType' => $returnedResponse["status"]
                ]
            );
        }
        return response()->json([
            'message' => 'Something went wrong',
            'alertType' => 'error'
        ]);
    }
}
