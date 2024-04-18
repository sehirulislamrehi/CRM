<?php

namespace App\Services\Backend\DatatableServices\UserModule\User;

use App\Models\Backend\UserModule\User;
use Yajra\DataTables\Facades\DataTables;

class UserDatatableService
{
    function getUserData($data): \Illuminate\Http\JsonResponse
    {
        return DataTables::of($data)
            ->rawColumns(['user_group_id', 'role_id', 'fullname', "phone", 'username', 'is_active', 'action', 'service_center', 'bu'])
            ->addIndexColumn()
            ->editColumn('bu', function (User $user) {
                $bu = $user->business_unit->pluck('name');

                $html = '';
                foreach ($bu as $item) {
                    $html .= '<span class="badge badge-pill badge-primary">' . $item . '</span> ';
                }

                return $html;
            })
            ->editColumn('service_center', function (User $user) {
                $sc = $user->service_center->pluck('name');

                $html = '';
                foreach ($sc as $item) {
                    $html .= '<span class="badge badge-pill badge-primary">' . $item . '</span> ';
                }

                return $html;
            })
            ->editColumn('is_active', function (User $user) {
                if ($user->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->editColumn('user_group_id', function (User $user) {
                return $user->user_group->name;
            })
            ->editColumn('role_id', function (User $user) {
                return $user->role->name ?? 'N/A';
            })
            ->addColumn('action', function (User $user) {
                return '<a class="btn-block btn-sm"
                            data-content="' . route('admin.user-module.user.edit', ['id' => $user->id]) . '"
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
