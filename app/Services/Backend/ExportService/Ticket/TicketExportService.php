<?php

namespace App\Services\Backend\ExportService\Ticket;

use App\Services\Backend\ModuleServices\TicketingModule\Ticket\TicketingService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketExportService implements FromCollection, WithHeadings, WithMapping
{
    protected TicketingService $ticketingService;
    protected Request $request;

    public function __construct(TicketingService $ticketingService, Request $request)
    {
        $this->ticketingService = $ticketingService;
        $this->request = $request;
    }


    public function collection(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|\Illuminate\Support\Collection|array
    {
        return $this->ticketingService->admin_panel_data($this->request, true);

    }


    // Define the headings for your Excel file
    public function headings(): array
    {
        return [
            'Ticket No',
            'Customer Phone',
            'Agent',
            'Channel',
            'Status',
            'Created at',
            'Updated at'
            // Add more columns as needed
        ];
    }


    public function map($row): array
    {
        return [
            $row->ticket_no,
            $row->customer_phone,
            $row->user_name,
            $row->channel_name,
            $row->status,
            $row->created_at,
            $row->updated_at
        ];
    }
}
