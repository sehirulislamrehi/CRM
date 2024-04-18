<?php

namespace App\Services\Backend\DatatableServices\CommonModule\Thana;

use App\Models\Backend\CommonModule\Thana;
use Yajra\DataTables\Facades\DataTables;

class ThanaDatatableService
{
    function getThanaData($data): \Illuminate\Http\JsonResponse
    {
        return DataTables::of($data)
            ->rawColumns(['is_active', 'action','district'])
            ->addIndexColumn()
            ->editColumn('is_active', function (Thana $thana) {
                if ($thana->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->addColumn('district',function(Thana $thana){
                return $thana->district->name;
            })
            ->editColumn('created_at', function (Thana $thana) {
                return $thana->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function (Thana $thana) {
                return $thana->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function (Thana $thana) {
                return '<a class="btn-block btn-sm"
                            data-content="' . route('admin.common-module.thana.modal.update', ['id' => $thana->id]) . '"
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
