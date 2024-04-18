<?php

namespace App\Services\Backend\DatatableServices\TicketModule\Ticket;

use App\Models\Backend\TicketingModule\Ticket;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\QueryDataTable;

class TicketDataTableService
{
    //Agent Panel
    public function getTicketData($data): \Illuminate\Http\JsonResponse
    {
        if (empty($data)) {
            // Return an empty DataTable response or any other suitable response
            return $this->emptyDataTableResponse();
        }
        return DataTables::of($data)
            ->rawColumns(['status', 'action'])
            ->editColumn('status', function (Ticket $ticket) {
                return $this->generateStatusBadge($ticket->status);
            })
            ->editColumn('action', function (Ticket $ticket) {
                return $this->generateActionButtons($ticket);
            })
            ->addIndexColumn()
            ->make(true);
    }

    private function generateStatusBadge($status): string
    {
        $badgeClass = [
            'pending' => 'badge-info',
            'on-process' => 'badge-warning',
            'done' => 'badge-success',
            'cancel' => 'badge-danger',
        ];

        return '<span class="badge ' . $badgeClass[$status] . '">' . __('ticket.' . $status) . '</span>';
    }

    private function generateActionButtons(Ticket $ticket): string
    {
        $phone = $ticket->customer->phone;
        $agent = $ticket->user->username;
        $channel = $ticket->channel->channel_number;

        $detailsRoute = route('ticket.get.ticket.details.modal', ['ticket_id' => $ticket->id]);
        $editRoute = route('ticket.get.ticket.edit', ['ticket_id' => $ticket->id, 'agent_id' => $ticket->user_id]);

        return '<div class="d-flex justify-content-between align-items-center">
            <a class="btn-block btn-sm" data-content="' . $detailsRoute . '" data-target="#largeModal" class="btn btn-outline-dark" data-toggle="modal" style="cursor: pointer;">
                <i class="fas fa-eye"></i>
            </a>
            <a class="btn-block btn-sm mt-0" href="' . $editRoute . '?phone_number=' . $phone . '&agent=' . $agent . '&channel=' . $channel . '" class="btn btn-outline-dark" target="_blank" style="cursor: pointer;">
                <i class="fas fa-edit"></i>
            </a>
        </div>';
    }

    private function emptyDataTableResponse()
    {
        return response()->json(['message' => __('ticket.empty_datatable')], 200);
    }

    //End agent panel

    private function generateActionButtonsAdmin($ticket): string
    {
        $detailsRoute = route('ticket.admin.ticket-details.admin-modal', ['ticket_id' => $ticket->id]);
        $editRoute = route('ticket.admin.ticket-update.admin.modal', ['ticket_id' => $ticket->id]);
        return '<div class="d-flex justify-content-center align-items-center">
        <a class="btn btn-sm btn-primary mx-1" data-content="' . $detailsRoute . '" data-target="#extraLargeModal" class="btn btn-outline-dark" data-toggle="modal" style="cursor: pointer;" data-toggle="tooltip" title="View Details">
            <i class="fas fa-eye"></i>
        </a>
        <a class="btn btn-sm btn-primary mx-1" data-content="' . $editRoute . '" data-target="#myModal" class="btn btn-outline-dark" data-toggle="modal" style="cursor: pointer;" data-toggle="tooltip" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    </div>';
    }


    /**
     * @throws Exception
     */
    public function getAdminPanelData($query): \Illuminate\Http\JsonResponse
    {
        if (empty($query)) {
            // Return an empty DataTable response or any other suitable response
            return $this->emptyDataTableResponse();
        }

        return (new QueryDataTable($query))
            ->rawColumns(['status','action'])
            ->editColumn('status', function ($row) {
                $status = $row->status;
                return $this->generateStatusBadge($status);
            })
            ->editColumn('action', function ($row) {
                return $this->generateActionButtonsAdmin($row);
            })
            ->make(true);
    }


}
