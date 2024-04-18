<?php

namespace App\Services\Backend\DatatableServices\CommonModule\ServiceCenter;

use App\Models\Backend\CommonModule\ServiceCenter;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ServiceCenterDatatableService
{
    /**
     * @param $data
     * @return JsonResponse
     * @throws \Exception
     */
    function getServiceCenterData($data): JsonResponse
    {
        return DataTables::of($data)
            ->rawColumns(['is_active', 'action','bu'])
            ->addIndexColumn()
            ->editColumn('bu', function (ServiceCenter $sc) {
                $businessUnits = $sc->business_unit->pluck('name');

                $html = '';
                foreach ($businessUnits as $bu) {
                    $html .= '<span class="badge badge-pill badge-primary">' . $bu . '</span> ';
                }

                return $html;
            })
            ->editColumn('is_active', function (ServiceCenter $su) {
                if ($su->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->addColumn('action', function (ServiceCenter $su) {
                return '<a class="btn-block btn-sm"
                            data-content="' . route('admin.common-module.service-center.modal.update', ['id' => $su->id]) . '"
                            data-target="#largeModal"
                            class="btn btn-outline-dark"
                            data-toggle="modal"
                            style="cursor: pointer;">
                                <i class="fas fa-edit"></i>Edit
                        </a>';
            })
            ->make(true);
    }
}
