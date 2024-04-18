<?php

namespace App\Http\Controllers;

use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\TicketingModule\Ticket;
use App\Models\Backend\UserModule\User;
use App\Services\Backend\DashboardService;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    /**
     * @param DashboardService $dashboardService
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }


    function index(): View
    {
        if (auth("web")->check()) {
            $userId = auth('web')->user()->id;
            $user = User::find($userId);
            if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                $business_units=BusinessUnit::all();
            } else {
                $userBusinessUnit = User::with('business_unit')->find($userId);
                $assignedBusinessUnits = $userBusinessUnit->business_unit->pluck('id')->toArray();
                $business_units = BusinessUnit::whereIn('id', $assignedBusinessUnits)->where('is_active', true)->orderBy("id", "ASC")->get();
            }
        } else {
            $business_units = BusinessUnit::where('is_active', true)->orderBy('id', "ASC")->get();
        }
        return view("backend.modules.dashboard", compact("business_units"));
    }

    function getStatusCount(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->dashboardService->getTicketCount());
    }

    function getTicketProgressByBusinessUnit(int $bu_id): \Illuminate\Http\JsonResponse
    {
        $bu = BusinessUnit::findOrFail($bu_id);
        $data = $this->dashboardService->ticketProgressDataByBusinessUnit($bu->id);
        return response()->json($data);

    }

    function lastFiveTicket(): \Illuminate\Http\JsonResponse
    {
        return $this->dashboardService->getLastFiveTicket()->make(true);

    }

    function getLastFiveAgentByTicket(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->dashboardService->getLastFiveAgentByTicket());
    }

    function getMostAriseCategoryProblem(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->dashboardService->fetchMostAriseCategoryProblem());
    }

    function getMostAriseProblem(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->dashboardService->fetchMostAriseProblem());
    }
}
