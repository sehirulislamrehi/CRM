<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\CommonModule\Brand;

use App\Http\Controllers\Controller;
use App\Models\Backend\CommonModule\Brand;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\UserModule\User;
use App\Services\Backend\DatatableServices\CommonModule\Brand\BrandDatatableService;
use App\Services\Backend\ModuleServices\CommonModule\Brand\BrandService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class BrandController extends Controller
{
    protected BrandDatatableService $brandDatatableService;
    protected BrandService $brandService;

    /**
     * @param BrandDatatableService $brandDatatableService
     * @param BrandService $brandService
     */
    public function __construct(BrandDatatableService $brandDatatableService, BrandService $brandService)
    {
        $this->brandDatatableService = $brandDatatableService;
        $this->brandService = $brandService;
    }


    private function validateRequest(Request $request): array
    {
        return $request->validate([
            "name" => "required",
            'business_unit_id' => 'required|exists:business_units,id',
            "is_active" => "required"
        ]);
    }

    function index(): View
    {
        if (can('brand_index')) {
            return view('backend.modules.common_module.brand.index');
        }
        return view('error.403');
    }

    function data(): \Illuminate\Http\JsonResponse
    {
        if (can('brand_index')) {
            if (auth("web")->check()) {
                $userId = auth('web')->user()?->id;
                $user = User::find($userId);
                $userBusinessUnit = User::with("business_unit")->find($userId)?->business_unit?->pluck("id")->toArray();
                if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                    $data = Brand::orderBy("id","desc");
                } else {
                    $data = Brand::whereIn("business_unit_id", $userBusinessUnit)->orderBy("id","desc");
                }
            } else {
                $data = Brand::orderBy("id","desc");
            }
            return $this->brandDatatableService->getBrandData($data);
        }
        return response()->json([
            'message' => 'Unauthorized Access',
            'alertType' => 'warning'
        ]);
    }

    function create(Request $request): \Illuminate\Http\JsonResponse
    {
        if (can('brand_index')) {
            $this->validateRequest($request);
            try {
                $brand = new Brand();
                $brand->name = $request->input('name');
                $brand->business_unit_id = $request->input('business_unit_id');
                $brand->is_active = $request->input('is_active');
                if ($brand->save()) {
                    $alert = array(
                        'message' => 'Successfully Created',
                        'alertType' => 'success'
                    );
                    return response()->json($alert);
                }
            } catch (\Throwable $th) {
                $alert = array(
                    'message' => $th->getMessage(),
                    'alertType' => 'error'
                );
                return response()->json($alert);

            }
            $alert = array(
                'message' => 'Something went wrong',
                'alertType' => 'error'
            );
            return response()->json($alert);
        }
        return response()->json([
            'message' => 'Unauthorized Access',
            'alertType' => 'warning'
        ]);
    }

    function update_modal(int $id): View
    {
        if (can('brand_index')) {
            $brand = Brand::find($id);
            if ($brand) {
                $business_units = $this->getBusiness_units();
                return view("backend.modules.common_module.brand.modals.edit", compact("brand", "business_units"));
            }
        }
        return view('error.modals.403');
    }

    function create_modal(): View
    {
        if (can('brand_index')) {
            $business_units = $this->getBusiness_units();
            return view("backend.modules.common_module.brand.modals.create", compact("business_units"));
        }

        return view('error.modals.403');
    }

    function bulk_modal(): View
    {
        if (can('service_center_index')) {
            $business_units = $this->getBusiness_units();
            return view('backend.modules.common_module.brand.modals.bulk_upload', compact('business_units'));
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
//            dd($this->brandService->bulk_upload($file,$bu_id));
            $returnedResponse = $this->brandService->bulk_upload($file, $bu_id);

            return response()->json([
                "message" => $returnedResponse["message"],
                "alertType" => $returnedResponse["status"]
            ]);

        }
        return response()->json([
            'message' => 'Something went wrong',
            'alertType' => 'error'
        ]);
    }

    function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (can('brand_index')) {
            $brand = Brand::find($id);

            if ($brand) {
                // Validation
                $this->validateRequest($request);
                try {

                    $brand->name = $request->input("name");
                    $brand->business_unit_id = $request->input('business_unit_id');
                    $brand->is_active = $request->input("is_active");

                    if ($brand->save()) {
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
            'alertType' => 'error'
        ]);

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getBusiness_units(): \Illuminate\Support\Collection
    {
        if (auth("web")->check()) {
            $userId = auth("web")->user()->id;
            $user = User::find($userId);
            if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                $business_units = BusinessUnit::where("is_active", true)->get();
            } else {
                $business_units = DB::table("business_units")
                    ->join("business_unit_user", "business_unit_user.business_unit_id", "=", "business_units.id")
                    ->where("business_unit_user.user_id", $userId)->get();
            }

        } else {
            $business_units = BusinessUnit::where("is_active", true)->get();
        }
        return $business_units;
    }
}
