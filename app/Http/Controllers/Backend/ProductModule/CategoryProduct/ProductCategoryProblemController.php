<?php

namespace App\Http\Controllers\Backend\ProductModule\CategoryProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductModule\ProductCategory\Category_Problem\ProductCategoryCreateProblemRequest;
use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\ProductModule\ProductCategoryProblem;
use App\Services\Backend\DatatableServices\ProductModule\ProductCategory\ProductProblemDatatableService;
use App\Services\Backend\ModuleServices\ProductModule\ProductCategory\ProductCategoryProblemService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductCategoryProblemController extends Controller
{
    protected ProductProblemDatatableService $productProblemDatatableService;
    protected ProductCategoryProblemService $productCategoryProblemService;


    //Start problem crud

    /**
     * @param ProductProblemDatatableService $productProblemDatatableService
     * @param ProductCategoryProblemService $productCategoryProblemService
     */
    public function __construct(ProductProblemDatatableService $productProblemDatatableService, ProductCategoryProblemService $productCategoryProblemService)
    {
        $this->productProblemDatatableService = $productProblemDatatableService;
        $this->productCategoryProblemService = $productCategoryProblemService;
    }

    function get_problem_list($product_category_id)
    {
        if (can('product_category_index')) {
            $pc = ProductCategory::find($product_category_id);
            if ($pc) {
                return view('backend.modules.product_module.product_category_problem.index', compact('pc'));
            }
        }
        return view('error.403');
    }

    function create_problem(ProductCategoryCreateProblemRequest $request)
    {
        if (can('product_category_index')) {
            try {
                if ($this->productCategoryProblemService->createProductCategoryProblem($request)) {
                    $alert = array(
                        'message' => 'Successfully Done',
                        'alert-type' => 'success'
                    );
                } else {
                    $alert = array(
                        'message' => 'Something went wrong',
                        'alert-type' => 'error'
                    );
                }
                return response()->json($alert);
            } catch (\Throwable $th) {
                $alert = array(
                    'message' => $th->getMessage(),
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($alert);
            }
        }
        return view('error.403');
    }

    function problem_data($pc_id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        if (can('product_category_index')) {
            $product_problems = ProductCategoryProblem::where('product_category_id', $pc_id)->get();
            return $this->productProblemDatatableService->getProductProblemData($product_problems);
        }
        return view('error.403');
    }

    function create_modal($pc_id)
    {
        if (can('product_category_index')) {
            $pc = ProductCategory::findOrFail($pc_id);
            return view('backend.modules.product_module.product_category_problem.modals.create', compact('pc'));
        }
        return view('error.modals.403');
    }

    function edit_modal(Request $request, $problem_id)
    {
        if (can('product_category_index')) {
            $problem = ProductCategoryProblem::find($problem_id);
            if ($problem) {
                return view('backend.modules.product_module.product_category_problem.modals.edit', compact('problem'));
            }
        }
        return view('error.modals.403');

    }

    function problem_update(Request $request, $problem_id)
    {
        if (can('product_category_index')) {
            $problem = ProductCategoryProblem::find($problem_id);
            if ($problem) {
                $request->validate([
                    'name' => [
                        'required',
                        Rule::unique('product_category_problems')->ignore($problem_id, 'id')->where(function ($query) use ($problem) {
                            return $query->where('product_category_id', $problem->product_category_id);
                        }),
                    ],
                    'is_active' => 'required',
                ]);
                try {
                    $problem->name = $request->input('name');
                    $problem->name_bn=$request->input('name_bn') ?? '';
                    $problem->is_active = $request->input('is_active');
                    if ($problem->save()) {
                        $alert = array(
                            'message' => 'Successfully Done',
                            'alert-type' => 'success'
                        );
                    } else {
                        $alert = array(
                            'message' => 'Something went wrong',
                            'alert-type' => 'error'
                        );
                    }
                    return response()->json($alert);
                } catch (\Throwable $th) {
                    $alert = array(
                        'message' => 'Something went wrong',
                        'alert-type' => 'error'
                    );
                    return response()->json($alert);
                }
            } else {
                $alert = array(
                    'message' => 'Resource not found',
                    'alert-type' => 'error'
                );
                return response()->json($alert);
            }
        }
        return view('error.403');
    }
    //End problem crud
}
