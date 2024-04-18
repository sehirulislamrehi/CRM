<?php

namespace App\Services\Backend\DatatableServices\CommonModule\Channel;

use App\Models\Backend\CommonModule\Channel;
use Yajra\DataTables\Facades\DataTables;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ChannelDatatableService
{
    function getChannelData($data): \Illuminate\Http\JsonResponse
    {
        return DataTables::of($data)
            ->rawColumns(['is_active', 'action', 'channel_logo'])
            ->addIndexColumn()
            ->editColumn('channel_logo', function (Channel $ch) {
                return '<img src="' . asset(get_base_path($ch->logo)) . '" class="img-thumbnail" alt="">';
            })
            ->editColumn('is_active', function (Channel $ch) {
                if ($ch->is_active) {
                    return '<span class="badge badge-success">active</span>';
                } else {
                    return '<span class="badge badge-danger">inactive</span>';
                }
            })
            ->addColumn('action', function (Channel $ch) {
                return '<a class="btn-block btn-sm"
                            data-content="' . route('admin.common-module.channel.modal.update', ['id' => $ch->id]) . '"
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
