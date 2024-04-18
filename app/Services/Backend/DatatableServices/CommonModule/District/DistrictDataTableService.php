<?php

namespace App\Services\Backend\DatatableServices\CommonModule\District;

use App\Models\Backend\CommonModule\District;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class DistrictDataTableService
{
    /**
     * @param $data
     * @return JsonResponse
     * @throws \Exception
     */
    function getDistrictData($data): JsonResponse
    {
        return DataTables::of($data)
            ->rawColumns(['is_active', 'action'])
            ->addIndexColumn()
            ->editColumn('is_active', function (District $district) {
                if ($district->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->editColumn('created_at', function (District $district) {
                return $district->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function (District $district) {
                return $district->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function (District $district) {
                return '<a class="btn-block btn-sm"
                            data-content="' . route('admin.common-module.district.details', ['id' => $district->id]) . '"
                            data-target="#myModal"
                            class="btn btn-outline-dark"
                            data-toggle="modal"
                            style="cursor: pointer;">
                                <i class="fas fa-edit"></i> Edit
                        </a>';
            })
            ->make(true);
    }
}
