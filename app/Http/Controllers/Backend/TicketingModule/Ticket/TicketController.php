<?php

namespace App\Http\Controllers\Backend\TicketingModule\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TicketingModule\SingleCustomerCreateRequest;
use App\Http\Requests\Backend\TicketingModule\TicketCreateRequest;
use App\Models\Backend\CommonModule\Channel;
use App\Models\Backend\CommonModule\District;
use App\Models\Backend\CommonModule\Thana;
use App\Models\Backend\CustomerModule\Customer;
use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\TicketingModule\Ticket;
use App\Models\Backend\TicketingModule\TicketDetail;
use App\Models\Backend\UserModule\User;
use App\Services\Backend\DatatableServices\TicketModule\Ticket\TicketDataTableService;
use App\Services\Backend\ExportService\Ticket\TicketExportService;
use App\Services\Backend\ModuleServices\TicketingModule\Ticket\TicketingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;


class TicketController extends Controller
{

    protected TicketingService $ticketingService;
    protected TicketDataTableService $ticketDataTableService;


    /**
     * @param TicketingService $ticketingService
     * @param TicketDataTableService $ticketDataTableService
     */
    public function __construct(TicketingService $ticketingService, TicketDataTableService $ticketDataTableService)
    {
        $this->ticketingService = $ticketingService;
        $this->ticketDataTableService = $ticketDataTableService;
    }





    //Admin panel
    public function ticket_index(Request $request)
    {
        $districts = District::where('is_active', true)->get();
        $thanas = Thana::where('is_active', true)->get();
        $channels = Channel::where('is_active', true)->get();

        return view('backend.modules.ticket_module.admin_module.index', compact('districts', 'thanas', 'channels'));
    }




    public function ticket_details_admin(Request $request, $ticket_id)
    {
        if (can('ticket_index')) {
            $ticket = Ticket::findOrFail($ticket_id);
            return view('backend.modules.ticket_module.admin_module.modals.details', compact('ticket'));
        }
        return view('error.modals.403');
    }



    public function update_ticket_admin($ticket_id)
    {
        if (can('ticket_index')) {
            $ticket = Ticket::findOrFail($ticket_id);
            return view('backend.modules.ticket_module.admin_module.modals.edit', compact('ticket'));
        }
        return view('error.modals.403');

    }



    public function change_status(Request $request): \Illuminate\Http\JsonResponse
    {
        if (can('ticket_index')) {
            $request->validate([
                'tid' => 'required|exists:tickets,id',
                'status' => 'required|in:pending,on-process,cancel,done'
            ]);

            try {
                DB::beginTransaction();

                DB::table('tickets')->where('id',$request->input('tid'))->update(['status' => $request->input('status')]);

                $detailIds = DB::table('ticket_details')->where("ticket_id",$request->input('tid'))->pluck('id')->toArray();

                DB::table("ticket_details")->whereIn('id', $detailIds)->update(['status' => $request->input('status')]);

                DB::commit();

                return response()->json([
                    'message' => "Status changed successfully",
                    'alertType' => 'success',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => $e->getMessage(),
                    'alertType' => 'error',
                ]);
            }

        }
        return response()->json([
            'message' => "Unauthorized access",
            'alertType' => 'error',
        ]);


    }

    public function ticket_admin_data(Request $request): \Illuminate\Http\JsonResponse
    {
//        dd($this->ticketingService->admin_panel_data($request));

        $data = $this->ticketingService->admin_panel_data($request);
//        dd($this->ticketDataTableService->getAdminPanelData($data));
        return $this->ticketDataTableService->getAdminPanelData($data);
    }

    public function exportData(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new TicketExportService($this->ticketingService, $request), 'tickets.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    //End admin panel









    /** ======================================================================================AGENT PANEL ================================================================================================== */










    //Agent Panel
    public function agent(Request $request)
    {
        if (can('agent_panel')) {
            $agentUserName = $request->query('agent');
            $channel = $request->query('channel');
            $agent = User::where('username', trim($agentUserName))->where('is_active', true)->first();
            $channel = Channel::where('channel_number', $channel)->first();
            if (($agent && $agent->user_group->group === 'Agent') && $channel) {
                return view('backend.modules.ticket_module.agent_module.index', compact('agent', 'channel'));
            }
            return view('error.403')->with('errorText', __("ticket.permission_error"));
        }
        return view('error.403')->with('errorText', "Unauthorized Access");
    }

    public function getTicketDetailsModal($ticket_id): View
    {
        if (can('agent_panel')) {
            $ticket = Ticket::findOrFail($ticket_id);
            return view('backend.modules.ticket_module.agent_module.modals.ticket.details', compact('ticket'));
        }
        return view('error.403');
    }

    public function getPrerequisiteData($phone = null): \Illuminate\Http\JsonResponse
    {

        if (can('agent_panel')) {
            $brands = $this->ticketingService->getBrand();
            $productCategories = $this->ticketingService->getProductCategory();
            $businessUnits = $this->ticketingService->getBusinessUnit();
            $brandBusinessUnitMap = $this->ticketingService->getBrandBusinessUnitMapping();
            $brandCategoryMap = $this->ticketingService->getBrandCategoryMapping();
            $districts = $this->ticketingService->getAllDistrict();
            $thanas = $this->ticketingService->getAllThana();
            $districtThanaMap = $this->ticketingService->getDistrictThanaMap();
            $buProductMap = $this->ticketingService->getProductBusinessUnitMapping();
            $customerInfo = $this->ticketingService->getCustomerDetails($phone);
            return response()->json([
                'brands' => $brands,
                'productCategories' => $productCategories,
                'businessUnits' => $businessUnits,
                'brandCategoryMap' => $brandCategoryMap,
                'brandBusinessUnitMap' => $brandBusinessUnitMap,
                'businessUnitCategoryMap' => $buProductMap,
                'thanas' => $thanas,
                'districts' => $districts,
                'districtThanaMap' => $districtThanaMap,
                'customerInfo' => $customerInfo,
            ]);
        }
        return response()->json([
            'alertType' => 'error',
            'message' => 'unauthorized'
        ]);
    }

    public function getServiceCenterDataByThanaId($thana_id)
    {
        return $this->ticketingService->serviceCenterByThana($thana_id);
    }

    public function getServiceCenterDataByBuCategoryId($thana_id, $bu_id, $category_id = null)
    {
        return $this->ticketingService->serviceCenterByCategoryId($thana_id, $bu_id, $category_id);
    }

    public function getProductCategoryProblemWithNote($category_id): array
    {
        $productCategory = ProductCategory::find($category_id);
        if (!$productCategory) {
            return [
                'error' => 'Something went wrong'
            ];
        }
        return [
            'is_home_service' => $productCategory->is_home_service,
            'problems' => $this->ticketingService->categoryProblemByProductCategoryId($category_id),
            'notes' => $this->ticketingService->categoryNoteByProductCategoryId($category_id),
        ];
    }

    public function getThanaByDistrictId($dist_id)
    {
        return $this->ticketingService->getThanaByDistrictId($dist_id);
    }


    public function submitTicket(TicketCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        if (can('agent_panel')) {
            if ($this->ticketingService->createServiceTicket($request)) {
                return response()->json([
                    'status' => 'success',
                    'message' => __('ticket.ticket.response.create_success')
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => __('ticket.ticket.response.update_failed')
                ]);
            }
        }
        return response()->json([
            'alertType' => 'error',
            'message' => 'Unauthorized Access'
        ]);

    }

    public function getCustomerInfo($phone)
    {
        if (can('agent_panel')) {
            return $this->ticketingService->getCustomerDetails($phone);
        }
        return response()->json([
            'alertType' => 'error',
            'message' => 'Unauthorized Access'
        ]);
    }

    public function getTicket($agent, $phone, $channel)
    {
        if (can('agent_panel')) {
            $agent = User::where('username', $agent)->where('is_active', true)->first();

            if ($agent) {
                $customer = Customer::where('phone', $phone)->first();
                $channel = Channel::where('channel_number', $channel)->first();
                $data = Ticket::where('customer_id', $customer?->id)
                    ->where('channel_id', $channel?->id)
                    ->orderBy('id', 'DESC')
                    ->take(3)
                    ->get();

                return $this->ticketDataTableService->getTicketData($data);

            }

            return view('error.404'); // Agent not found
        }

        return view('error.403'); // No permission
    }


    public function edit_ticket_page($agent_id, $ticket_id, Request $request)
    {
        if (can('agent_panel')) {
            $channel_number = $request?->query('channel');
            $channel = Channel::where('channel_number', $channel_number)->first();
            if (!$channel) {
                return view('error.404');
            }
            $agent = User::where('id', $agent_id)->where('is_active', true)->first();
            if ($agent && $agent->user_group->group === 'Agent') {
                $agent_id = $agent->id;
                $ticket = Ticket::where('user_id', $agent_id)->where('id', $ticket_id)->first();
                $edit = true;//To prevent frontend script to load a blank ticket
                if ($ticket) {
                    return view('backend.modules.ticket_module.agent_module.index', compact('ticket', 'edit', 'agent', 'channel'));
                }
            }
            return view('error.404');
        }
        return view('error.403');

    }

    public function updateTicket(TicketCreateRequest $request)
    {
        if (can('agent_panel')) {
            if ($this->ticketingService->updateTicket($request)) {
                return response()->json([
                    'alertType' => 'success',
                    'message' => __('ticket.ticket.response.updated_success')
                ]);
            } else {
                return response()->json([
                    'alertType' => 'error',
                    'message' => __('ticket.ticket.response.update_failed')
                ]);
            }
        }
        return view('error.403');

    }

    public function saveCustomer(SingleCustomerCreateRequest $request)
    {
        if (can('agent_panel')) {
            try {
                if ($this->ticketingService->saveOrUpdateCustomerDetails($request)) {
                    return response()->json([
                        'alertType' => 'success',
                        'message' => __('ticket.customer.response.updated_success')
                    ]);
                }
            } catch (\Exception $th) {
                return response()->json([
                    'alertType' => 'error',
                    'message' => $th->getMessage()
                ]);
            }
        }
        return response()->json([
            'alertType' => 'error',
            'message' => 'Unauthorized'
        ]);
    }


}
