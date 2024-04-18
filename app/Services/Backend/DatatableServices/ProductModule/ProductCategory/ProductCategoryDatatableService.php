<?php

namespace App\Services\Backend\DatatableServices\ProductModule\ProductCategory;

use App\Models\Backend\ProductModule\ProductCategory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ProductCategoryDatatableService
{

    /**
     * @param Builder $productCategory
     * @return JsonResponse
     * @throws Exception
     */
    function getProductCategoryData(Builder $productCategory): JsonResponse
    {
        return DataTables::of($productCategory)
            ->addIndexColumn()
            ->rawColumns(['is_active','is_home_service', 'action', 'edit_note', 'problem','brand','business_unit','service_center'])
            ->editColumn('is_active', function (ProductCategory $pc) {
                if ($pc->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->editColumn('is_home_service', function (ProductCategory $pc) {
                if ($pc->is_home_service) {
                    return '<span class="badge badge-success">YES</span>';
                } else {
                    return '<span class="badge badge-danger">NO</span>';
                }
            })
            ->editColumn('brand', function (ProductCategory $pc) {
                return $pc->brand()->pluck('name')->map(function($name){
                    return '<span class="badge badge-primary">' . $name . '</span>';
                })->implode(' ');
            })
            ->editColumn('business_unit', function (ProductCategory $pc) {
                $brand = $pc->brand()->first();
                return $brand->businessUnit->name;
            })
            ->editColumn('service_center', function (ProductCategory $pc) {
                $serviceCenters = $pc->service_center()->pluck('name');
                if ($serviceCenters->isEmpty()) {
                    return 'All';
                }
                return $serviceCenters->map(function ($name) {
                    return '<span class="badge badge-primary">' . $name . '</span>';
                })->implode(' ');
            })
            ->editColumn('edit_note', function (ProductCategory $pc) {
                return '<a class="btn-block btn-sm" target="blank"
            href="' . route('admin.product-module.product-category-note.get_note', ['product_category_id' => $pc->id]) . '"
            style="cursor: pointer;">
                <i class="fas fa-eye"></i> View
            </a>';
            })
            ->editColumn('problem', function (ProductCategory $pc) {
                return '<a class="btn-block btn-sm" target="blank"
            href="' . route('admin.product-module.product-category-problem.get_problem_list', ['product_category_id' => $pc->id]) . '"
            style="cursor: pointer;">
                <i class="fas fa-eye"></i> View
            </a>';
            })
            ->editColumn('action', function (ProductCategory $pc) {
                return '<a class="btn-block btn-sm"
                        data-content="' . route('admin.product-module.product-category.details', ['id' => $pc->id]) . '"
                        data-target="#extraLargeModal"
                        class="btn btn-outline-dark"
                        data-toggle="modal"
                        style="cursor: pointer;">
                            <i class="fas fa-edit"></i>Edit
                    </a>';
            })
            ->make(true);
    }
}
