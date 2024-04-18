<?php

namespace App\Services\Backend\DatatableServices\UserModule\Role;

use App\Models\Backend\UserModule\Role;
use Yajra\DataTables\Facades\DataTables;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class RoleDatatableService
{
    function getAllRole($role): \Illuminate\Http\JsonResponse
    {
        return DataTables::of($role)
            ->addIndexColumn()
            ->rawColumns(['action', 'is_active'])
            ->editColumn('is_active', function (Role $role) {
                if ($role->is_active == true) {
                    return '<p class="badge badge-success">Active</p>';
                } else {
                    return '<p class="badge badge-danger">Inactive</p>';
                }
            })
            ->addColumn('action', function (Role $role) {
                return '<a class="btn-block btn-sm"
                                data-content="' . route('admin.user-module.role.modal.edit', ['id' => $role->id]) . '"
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
