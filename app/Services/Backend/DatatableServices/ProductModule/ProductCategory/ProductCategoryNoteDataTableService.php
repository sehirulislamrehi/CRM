<?php

namespace App\Services\Backend\DatatableServices\ProductModule\ProductCategory;

use App\Models\Backend\ProductModule\ProductCategoryNote;
use Yajra\DataTables\Facades\DataTables;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ProductCategoryNoteDataTableService
{
    function getProductCategoryNoteData($notes): \Illuminate\Http\JsonResponse
    {
        return DataTables::of($notes)
            ->rawColumns(['is_active', 'action', 'is_home_service'])
            ->addIndexColumn()
            ->editColumn('is_home_service', function (ProductCategoryNote $note) {
                if ($note->is_home_service) {
                    return '<span class="badge badge-success">Yes</span>';
                } else {
                    return '<span class="badge badge-danger">No</span>';
                }
            })
            ->editColumn('is_active', function (ProductCategoryNote $note) {
                if ($note->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->addColumn('action', function (ProductCategoryNote $note) {
                return '<a class="btn-block btn-sm"
                            data-content="' . route('admin.product-module.product-category-note.note.edit', ['id' => $note->id]) . '"
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
