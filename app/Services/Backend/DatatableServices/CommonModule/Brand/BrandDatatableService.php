<?php

namespace App\Services\Backend\DatatableServices\CommonModule\Brand;

use App\Models\Backend\CommonModule\Brand;
use Yajra\DataTables\Facades\DataTables;

class BrandDatatableService
{
    function getBrandData($data): \Illuminate\Http\JsonResponse
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->rawColumns(['business_unit', 'is_active', 'action'])
            ->editColumn('business_unit', function (Brand $brand) {
                return $brand->businessUnit->name;
            })
            ->editColumn('is_active', function (Brand $brand) {
                if ($brand->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->addColumn('action', function (Brand $brand) {
                return '<a class="btn-block btn-sm"
                            data-content="' . route('admin.common-module.brand.modal.update', ['id' => $brand->id]) . '"
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
