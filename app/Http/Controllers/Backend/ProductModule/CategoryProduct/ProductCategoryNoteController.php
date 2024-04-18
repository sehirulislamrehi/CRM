<?php

namespace App\Http\Controllers\Backend\ProductModule\CategoryProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductModule\ProductCategory\ProductCategoryNote\ProductCategoryCreateNoteRequest;
use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\ProductModule\ProductCategoryNote;
use App\Services\Backend\DatatableServices\ProductModule\ProductCategory\ProductCategoryNoteDataTableService;
use App\Services\Backend\ModuleServices\ProductModule\ProductCategory\ProductCategoryNoteService;
use Illuminate\Http\Request;

class ProductCategoryNoteController extends Controller
{
    protected ProductCategoryNoteService $productCategoryNoteService;
    protected ProductCategoryNoteDataTableService $productCategoryNoteDataTableService;


    /**
     * @param ProductCategoryNoteService $productCategoryNoteService
     * @param ProductCategoryNoteDataTableService $productCategoryNoteDataTableService
     */
    public function __construct(ProductCategoryNoteService $productCategoryNoteService, ProductCategoryNoteDataTableService $productCategoryNoteDataTableService)
    {
        $this->productCategoryNoteService = $productCategoryNoteService;
        $this->productCategoryNoteDataTableService = $productCategoryNoteDataTableService;
    }

    function get_note($product_category_id)
    {
        if (can('product_category_index')) {
            $pc = ProductCategory::findOrFail($product_category_id);
            if ($pc) {
                return view('backend.modules.product_module.product_category_note.index', compact('pc'));
            }
        }
        return view('error.403');
    }

    function note_data($pc_id)
    {
        if (can('product_category_index')) {
            $product_note = ProductCategoryNote::where('product_category_id', $pc_id)->get();
            return $this->productCategoryNoteDataTableService->getProductCategoryNoteData($product_note);
        }
        return view('error.modals.403');
    }

    function note_edit($note_id)
    {
        if (can('product_category_index')) {
            $note = ProductCategoryNote::find($note_id);
            if ($note) {
                return view('backend.modules.product_module.product_category_note.modals.edit', compact('note'));
            }
        }
        return view('error,modals.403');
    }

    function create_modal($product_category_id)
    {
        if (can('product_category_index')) {
            $pc = ProductCategory::find($product_category_id);
            return view('backend.modules.product_module.product_category_note.modals.create', compact('pc'));
        }
        return view('error.modals.403');
    }

    function note_update(Request $request, $id)
    {
        if (can('product_category_index')) {
            $request->validate([
                'note' => 'required',
                'is_active' => 'required',
            ]);
            try {
                $this->productCategoryNoteService->updateNote($request, $id);
                $alert = array(
                    'message' => 'Successfully Done',
                    'alertType' => 'success'
                );
                return response()->json($alert);

            } catch (\Throwable $th) {
                $alert = array(
                    'message' => 'Something went wrong',
                    'alertType' => 'error'
                );
                return response()->json($alert);

            }
        }
        return view('error.403');
    }

    function note_create(ProductCategoryCreateNoteRequest $request)
    {
        if (can('product_category_index')) {
            try {
                if ($this->productCategoryNoteService->createNote($request)) {
                    $alert = array(
                        'message' => 'Successfully Done',
                        'alertType' => 'success'
                    );
                } else {
                    $alert = array(
                        'message' => 'Something went wrong',
                        'alertType' => 'error'
                    );
                }
                return response()->json($alert);

            } catch (\Throwable $th) {
                $alert = array(
                    'message' => $th->getMessage(),
                    'alertType' => 'error'
                );
                return response()->json($alert);

            }
        }
        return view('error.403');
    }

    //End note section crud
}
