<?php

namespace App\Services\Backend;


use App\Models\Backend\TicketingModule\Ticket;
use App\Models\Backend\UserModule\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Facades\DataTables;

class DashboardService
{
    /**
     * @return array
     */
    public function getTicketCount(): array
    {
        if (auth('web')->check()) {
            $user = auth('web')->user();
            $user = (new \App\Models\Backend\UserModule\User)->find($user?->id);
            if ($user->user_group->name == "Agent") {
                $ticketPending = Ticket::where('user_id', $user?->id)->where("status", "pending")->count();
                $ticketOnProcess = Ticket::where('user_id', $user?->id)->where("status", "on-process")->count();
                $ticketDone = Ticket::where('user_id', $user?->id)->where("status", "done")->count();
                $ticketCancelled = Ticket::where('user_id', $user?->id)->where("status", "cancel")->count();
            } else {
                $buId = DB::table('business_unit_user')->where("user_id", $user?->id)->pluck("business_unit_id")->toArray();
                $ticketPending = Ticket::where('user_id', $user?->id)->where("status", "pending")->whereIn("business_unit_id", $buId)->count();
                $ticketOnProcess = Ticket::where('user_id', $user?->id)->where("status", "on-process")->whereIn("business_unit_id", $buId)->count();
                $ticketDone = Ticket::where('user_id', $user?->id)->where("status", "done")->whereIn("business_unit_id", $buId)->count();
                $ticketCancelled = Ticket::where('user_id', $user?->id)->where("status", "cancel")->whereIn("business_unit_id", $buId)->count();
            }
        } else {
            $ticketPending = Ticket::where("status", "pending")->count();
            $ticketOnProcess = Ticket::where("status", "on-process")->count();
            $ticketDone = Ticket::where("status", "done")->count();
            $ticketCancelled = Ticket::where("status", "cancel")->count();
        }
        return [
            'pending' => $ticketPending,
            'on_process' => $ticketOnProcess,
            'done' => $ticketDone,
            'cancelled' => $ticketCancelled,
        ];
    }

    /**
     * In a particular business unit how many ticket are in different status
     * @param $bu_id
     * @return Collection
     */
    function ticketProgressDataByBusinessUnit($bu_id): Collection
    {
        return DB::table('ticket_details')->select('status', DB::raw('COUNT(*) as count'))
            ->where('business_unit_id', $bu_id)->groupBy('status')->get();
    }


    /**
     * Last five ticket
     * @return DataTableAbstract
     * @throws Exception
     */
    function getLastFiveTicket(): DataTableAbstract
    {
        $ticket = (new \App\Models\Backend\TicketingModule\Ticket)->latest()->take(5);
        return DataTables::of($ticket)
            ->rawColumns(['status', 'created_at', 'updated_at', 'action'])
            ->editColumn('status', function (Ticket $ticket) {
                return $this->generateStatusBadge($ticket->status);
            })
            ->editColumn('created_at', function (Ticket $ticket) {
                return $ticket->created_at->format('d-m-Y h:i:s A');
            })
            ->editColumn('updated_at', function (Ticket $ticket) {
                return $ticket->updated_at->format('d-m-Y h:i:s A');
            })
            ->addIndexColumn();
    }

    /**
     * Generate badge according to status
     * @param $status
     * @return string
     */
    private function generateStatusBadge($status): string
    {
        $badgeClass = [
            'pending' => 'badge-info',
            'on-process' => 'badge-warning',
            'done' => 'badge-success',
            'cancel' => 'badge-danger',
        ];

        return '<span class="badge ' . $badgeClass[$status] . '">' . $status . '</span>';
    }

    /**
     * Top five agent who create most of the ticket
     * @return Collection
     */
    public function getLastFiveAgentByTicket(): Collection
    {
        return DB::table('tickets')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select('users.username', DB::raw('COUNT(*) as count'))
            ->groupBy('users.username')
            ->get();
    }


    /**
     * Most arise category problem by month
     * @return array
     */
    public function fetchMostAriseCategoryProblem(): array
    {
        $twoMonthsAgo = date('Y-m-01', strtotime('-2 months'));

        $data = DB::table('ticket_details')
            ->join('product_categories', 'product_categories.id', '=', 'ticket_details.product_category_id')
            ->select(
                DB::raw('YEAR(ticket_details.created_at) as year'),
                DB::raw('MONTHNAME(ticket_details.created_at) as month'),
                'product_categories.name as category_name',
                DB::raw('COUNT(*) as total_tickets')
            )
            ->where('ticket_details.created_at', '>=', $twoMonthsAgo)
            ->groupBy('year', 'month', 'category_name')
            ->orderBy('year')
            ->orderByRaw('MONTH(ticket_details.created_at)')
            ->orderBy('total_tickets', 'desc')
            ->get();
        $formattedData = [];
        foreach ($data as $datum) {
            if (isset($formattedData[$datum->month])) {
                if (count($formattedData[$datum->month]) < 3) {
                    $formattedData[$datum->month][$datum->category_name] = $datum->total_tickets;
                }
            } else {
                $formattedData[$datum->month][$datum->category_name] = $datum->total_tickets;
            }
        }

        return $formattedData;
    }

    /**
     * @return Collection
     */
    public function fetchMostAriseProblem(): Collection
    {
        return DB::table('ticket_details')
            ->join('product_categories','product_categories.id',"=","ticket_details.product_category_id")
            ->select('product_categories.name as category_name',
                DB::raw('COUNT(*) as total_tickets'))
            ->groupBy("category_name")
            ->orderBy("total_tickets","DESC")
            ->take(5)
            ->get();

    }


}
