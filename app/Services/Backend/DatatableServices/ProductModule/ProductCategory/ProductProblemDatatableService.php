<?php

namespace App\Services\Backend\DatatableServices\ProductModule\ProductCategory;

use App\Models\Backend\ProductModule\ProductCategoryProblem;
use Yajra\DataTables\Facades\DataTables;

class ProductProblemDatatableService
{
    function getProductProblemData($data): \Illuminate\Http\JsonResponse
    {
        return DataTables::of($data)
            ->rawColumns(['is_active', 'action'])
            ->addIndexColumn()
            ->editColumn('is_active', function (ProductCategoryProblem $problem) {
                if ($problem->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->addColumn('action', function (ProductCategoryProblem $problem) {
                return '<a class="btn-block btn-sm"
                            data-content="' . route('admin.product-module.product-category-problem.modal.edit', ['id' => $problem->id]) . '"
                            data-target="#myModal"
                            class="btn btn-outline-dark"
                            data-toggle="modal"
                            style="cursor: pointer;">
                                <i class="fas fa-edit"></i>Edit
                        </a>';
            })
            ->make(true);
    }
}
