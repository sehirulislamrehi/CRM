<?php

namespace App\Http\Controllers\Backend\ProductModule\CategoryProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductModule\ProductCategory\ProductCategoryRequest;
use App\Models\Backend\CommonModule\Brand;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\CommonModule\ServiceCenter;
use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\UserModule\User;
use App\Services\Backend\DatatableServices\ProductModule\ProductCategory\ProductCategoryDatatableService;
use App\Services\Backend\ModuleServices\ProductModule\ProductCategory\ProductCategoryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use PHPUnit\Framework\Constraint\Count;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    protected ProductCategoryService $productCategoryService;
    protected ProductCategoryDatatableService $productCategoryDatatableService;

    public function __construct(ProductCategoryService $productCategoryService, ProductCategoryDatatableService $productCategoryDatatableService)
    {
        $this->productCategoryService = $productCategoryService;
        $this->productCategoryDatatableService = $productCategoryDatatableService;
    }

    //Start product category crud start
    function index(): View
    {
        if (can('product_category_index')) {
            return view('backend.modules.product_module.product_category.index');
        }

        return view('error.403');
    }

    /**
     * @throws Exception
     */
    function data(): \Illuminate\Http\JsonResponse
    {
        if (can('product_category_index')) {
            try {
                if (auth("web")->check()) {
                    $userId = auth('web')->user()?->id;
                    $user = User::find($userId);
                    if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                        $data = ProductCategory::query()->orderBy('id', 'DESC');
                    } else {
                        $userBusinessUnit = User::with("business_unit")->find($userId)?->business_unit?->pluck("id")->toArray();
                        $brand_data = Brand::whereIn("business_unit_id", $userBusinessUnit)->pluck("id")->toArray();
                        $product_category_ids = DB::table("brand_product_category")
                            ->join("brands", "brand_product_category.brand_id", "=", "brands.id")
                            ->select("brand_product_category.product_category_id as category_id")
                            ->whereIn("brands.id", $brand_data);
                        $data = ProductCategory::query()->whereIn("id", $product_category_ids)->orderBy('id', 'DESC');
                    }
                } else {
                    $data = ProductCategory::query()->orderBy('id', 'DESC');
                }
                return $this->productCategoryDatatableService->getProductCategoryData($data);
            } catch (Exception $th) {
                $alert = [
                    'message' => $th->getMessage(),
                    'alertType' => 'error',
                ];
                return response()->json($alert);

            }
        }
        $alert = [
            'message' => "Unauthorized",
            'alertType' => 'error',
        ];
        return response()->json($alert);


    }

    function create_modal(): View
    {
        if (can('product_category_index')) {
            $brands = $this->productCategoryService->getIndexData();
            $service_centers=$this->productCategoryService->getUserBasedServiceCenterList();
            return view('backend.modules.product_module.product_category.modals.create', compact('brands',"service_centers"));
        }
        return view('error.modals.403');

    }

    function create(ProductCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        if (can('product_category_index')) {
            try {
                $this->productCategoryService->createProductCategory($request);
                return response()->json([
                    'message' => 'Successfully Done',
                    'alertType' => 'success',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => $th->getMessage(),
                    'alertType' => 'error',
                ]);
            }
        }
        return response()->json([
            'message' => "Unauthorized",
            'alertType' => 'error',
        ]);
    }

    function details(Request $request, $id): View
    {
        if (can('product_category_index')) {
            $product_category = ProductCategory::find($id);
            if ($product_category) {
                $brands = $this->productCategoryService->getIndexData();
                $selected_brand = $product_category->brand()->pluck('id')->toArray();
                $service_centers=$this->productCategoryService->getUserBasedServiceCenterList();
                $selected_service_center=DB::table("product_category_service_center")->where("product_category_id",$product_category->id)->pluck("service_center_id")->toArray();
                return view('backend.modules.product_module.product_category.modals.edit', compact('brands', 'selected_brand', 'product_category',"service_centers","selected_service_center"));
            }
        }
        return view('error.modals.403');

    }

    function bulk_modal(): View
    {
        if (can('product_category_index')) {
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
            return view('backend.modules.product_module.product_category.modals.bulk_upload', compact('business_units'));
        }
        return view('error.modals.403');
    }

    function bulk_upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => 'required',
            'bu_id' => 'exists:business_units,id'
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
            $buId = $request->input('bu_id');
//            dd($this->productCategoryService->bulk_upload($file,$buId));
            $returnedResponse = $this->productCategoryService->bulk_upload($file, $buId);
            return response()->json([
                'message' => $returnedResponse['message'],
                "alertType" => $returnedResponse['status']
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong',
            'alertType' => 'error'
        ]);
    }

    //end product category crud start

    function update(ProductCategoryRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        if (can('product_category_index')) {
            $result = $this->productCategoryService->updateCategory($request, $id);

            if ($result) {
                $alert = [
                    'message' => 'Data saved successfully',
                    'alertType' => 'success',
                ];
            } else {
                $alert = [
                    'message' => 'Category not found',
                    'alertType' => 'error',
                ];
            }

            return response()->json($alert);
        }

        $alert = [
            'message' => 'Unauthorized',
            'alertType' => 'error',
        ];
        return response()->json($alert);

    }
}
