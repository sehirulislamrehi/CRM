<?php

namespace App\Services\Backend\ModuleServices\TicketingModule\Ticket;

use App\Exceptions\Module\TicketModule\Ticket\TicketNotFoundException;
use App\Models\Backend\CommonModule\Brand;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\CommonModule\Channel;
use App\Models\Backend\CommonModule\District;
use App\Models\Backend\CommonModule\Thana;
use App\Models\Backend\CustomerModule\Customer;
use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\ProductModule\ProductCategoryProblem;
use App\Models\Backend\TicketingModule\Ticket;
use App\Models\Backend\TicketingModule\TicketDetail;
use App\Models\Backend\UserModule\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use MongoDB\Driver\Query;


/**
 * TicketingService
 * @package App\Services\Backend\ModuleServices\TicketingModule\Ticket
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class TicketingService
{
    //Utility Functions
    public function getBrand()
    {
        return Brand::where('is_active', true)->get();
    }

    public function getProductCategory()
    {
        return ProductCategory::where('is_active', true)->get();
    }

    public function getProductCategoryProblem()
    {
        return ProductCategoryProblem::where('is_active', true)->get();
    }

    public function getBusinessUnit()
    {
        return BusinessUnit::where('is_active', true)->get();
    }

    public function serviceCenterByThana($thanaId)
    {
        $thana = Thana::with(['service_center' => function (Builder $query) {
            $query->where('is_active', true)->select('id', 'name');
        }])->find($thanaId);

        return $thana->service_center;
    }

    public function serviceCenterByCategoryId($thana_id, $bu_id, $category_id): \Illuminate\Support\Collection
    {
        $data = DB::table('service_centers')
            ->join('business_unit_service_center', 'service_centers.id', '=', 'business_unit_service_center.service_center_id')
            ->join('business_units', 'business_units.id', '=', 'business_unit_service_center.business_unit_id')
            ->join('service_center_thana', 'service_centers.id', '=', 'service_center_thana.service_center_id')
            ->join('thanas', 'thanas.id', '=', 'service_center_thana.thana_id')
            ->select('service_centers.id as id', 'service_centers.name as name')
            ->where('thanas.id', $thana_id)
            ->where('business_units.id', $bu_id);

        if ($category_id != '') {
            $data = $data->leftJoin('product_category_service_center', function ($join) use ($category_id) {
                $join->on('service_centers.id', '=', 'product_category_service_center.service_center_id')
                    ->where('product_category_service_center.product_category_id', $category_id);
            });
        }

        return $data->get();
    }


    public function categoryNoteByProductCategoryId($category_id): array
    {
        $category = ProductCategory::with(['product_category_note' => function ($query) {
            $query->where('is_active', true);
        }])->findOrFail($category_id);
        $data = [];

        foreach ($category->product_category_note as $item) {
            $note = $item->note;

            $data[] = ['note' => $note];
        }

        return $data;
    }


    public function categoryProblemByProductCategoryId($category_id)
    {
        $langCode = App::getLocale();
        if ($langCode === 'bn') {
            $category = ProductCategory::with(['product_category_problem' => function ($query) use ($langCode) {
                $query->select('id', 'name_bn as name', 'product_category_id');
            }])->findOrFail($category_id);
        } else {
            $category = ProductCategory::with(['product_category_problem' => function ($query) use ($langCode) {
                $query->select('id', 'name', 'product_category_id');
            }])->findOrFail($category_id);
        }

        return $category->product_category_problem;
    }


    public function getProductBusinessUnitMapping(): array
    {
        $businessUnits = BusinessUnit::with(['brands.product_category'])->get();

        $result = [];

        foreach ($businessUnits as $businessUnit) {
            $businessUnitId = $businessUnit->id;
            $categoryIds = [];

            foreach ($businessUnit->brands as $brand) {
                foreach ($brand->product_category as $category) {
                    $categoryIds[] = $category->id;
                }
            }

            $result[] = [
                'business_unit_id' => $businessUnitId,
                'category_ids' => $categoryIds,
            ];
        }

        return $result;

    }


    public function getThanaByDistrictId($district_id)
    {
        $dist = District::with('thana')->findOrFail($district_id);
        return $dist->thana;
    }

    public function getBrandBusinessUnitMapping(): array
    {
        $brands = $this->getBrand();
        $brandBusinessMap = [];
        foreach ($brands as $brand) {
            $brandBusinessMap[] = [
                'brand_id' => $brand->id,
                'business_unit_id' => $brand->business_unit_id,
            ];
        }
        // Sort the array based on brand_id
        usort($brandBusinessMap, function ($a, $b) {
            return $a['brand_id'] - $b['brand_id'];
        });
        return $brandBusinessMap;
    }

    public function getBrandCategoryMapping(): array
    {
        $brands = Brand::with('product_category')->get();
        $brandCategoryMap = [];
        foreach ($brands as $brand) {
            $data = ['brand_id' => $brand->id];
            if (!empty($brand->product_category)) {
                $categoryIds = [];
                foreach ($brand->product_category as $category) {
                    $categoryIds[] = $category->id;
                }
                $data['category_id'] = $categoryIds;
            } else {
                $data['category_id'] = [];
            }
            $brandCategoryMap[] = $data;
        }

        // Sort the array based on brand_id
        usort($brandCategoryMap, function ($a, $b) {
            return $a['brand_id'] - $b['brand_id'];
        });

        return $brandCategoryMap;
    }


    public function getAllThana()
    {
        return Thana::where('is_active', true)->get();
    }

    public function getAllDistrict()
    {
        return District::where('is_active', true)->get();
    }

    public function getDistrictThanaMap(): array
    {
        $districts = District::with('thana')->where('is_active', true)->get();
        $districtThanaMap = [];

        foreach ($districts as $district) {
            $data = ['district_id' => $district->id];

            if (!empty($district->thana)) {
                $thanaIds = [];

                foreach ($district->thana as $thana) {
                    $thanaIds[] = $thana->id;
                }

                $data['thana_id'] = $thanaIds;
            } else {
                $data['thana_id'] = [];
            }

            $districtThanaMap[] = $data;
        }

        return $districtThanaMap;
    }

    //End utility functions
    public function createServiceTicket($request, $tid = null): bool|string
    {
        try {
            $user = User::where('username', $request['agentUserName'])->firstOrFail();
            if ($user->role->name !== 'Agent') {
                return false; // Agent not authorized
            }

            $channel = Channel::where('channel_number', $request['channelNumber'])->first();
            if (!$channel) {
                return false;
            }
            DB::beginTransaction();

            $customerId = $this->saveOrUpdateCustomer($request);
            $ticketId = $this->saveOrUpdateTicket($tid, $customerId, $user, $request, $channel);
            $ticketDetailIds = $this->saveOrUpdateTicketDetails($request, $ticketId);
            $this->saveOrUpdateTicketCategoryProblem($request, $ticketDetailIds);

            DB::commit();
            return true; // Ticket created successfully
        } catch (\Exception $th) {
            DB::rollBack();
            return $th->getMessage(); // Error occurred
        }
    }


    /**
     * @param $tid
     * @param $customerId
     * @param $user
     * @param $request
     * @param $channel
     * @return int
     */
    private function saveOrUpdateTicket($tid, $customerId, $user, $request, $channel): int
    {
        $ticketData = [
            'ticket_no' => $this->generateTicketId(),
            'customer_id' => $customerId,
            'user_id' => $user->id,
            'phone' => $request['phone'],
            'email' => $request['email'],
            'name' => $request['name'],
            'address' => $request['address'],
            'district_id' => $request['district_id'],
            'thana_id' => $request['thana_id'],
            'channel_id' => $channel->id,
            'status' => 'pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        if ($tid == null) {
            $ticketId = DB::table('tickets')->insertGetId($ticketData);
        } else {
            DB::table('tickets')->where('id', $tid)->update($ticketData);
            $ticketId = DB::table('tickets')->where('id', $tid)->first()->id;
        }
        return $ticketId;
    }

    /**
     * @param $request
     * @return int
     */
    private function saveOrUpdateCustomer($request): int
    {

        $customer = Customer::where('phone', trim($request['callerId']))->first();
        $customerDetail = [
            'alternative_phone' => $request['alternative_phone'] ?? null,
            'present_address' => $request['present_address'] ?? null,
            'permanent_address' => $request['permanent_address'] ?? null,
            'nid' => $request['nid'] ?? null,
            'gender' => $request['gender'] ?? null
        ];
        if ($customer) {
            $customerId = $customer->id;
            if ($request['thana_id'] != $customer->thana_id) {
                DB::table('customers')->where('id', $customerId)->update([
                    'thana_id' => $request['thana_id'],
                    'district_id' => $request['district_id'],
                ]);
            }
            $customer_detail = DB::table('customer_details')->where('customer_id', $customerId)->exists();
            if ($customer_detail) {
                DB::table('customer_details')->where('customer_id', $customerId)->update($customerDetail);
            } else {
                $customerDetail['customer_id'] = $customerId;
                DB::table('customer_details')->insert($customerDetail);
            }
        } else {
            $customerData = [
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['callerId'],
                'address' => $request['address'],
                'district_id' => (int)$request['district_id'],
                'thana_id' => (int)$request['thana_id'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $customerId = DB::table('customers')->insertGetId($customerData);
            $customerDetail['customer_id'] = $customerId;
            DB::table('customer_details')->insert($customerDetail);
        }
        return $customerId;
    }

    /**
     * @param $request
     * @param $ticketId
     * @return array
     */
    private function saveOrUpdateTicketDetails($request, $ticketId): array
    {
        $ticketDetailData = [];
        foreach ($request['business_units'] as $key => $value) {
            $data = [
                'ticket_id' => $ticketId,
                'business_unit_id' => $value,
                'brand_id' => $request['brands'][$key],
                'product_category_id' => $request['product_category'][$key],
                'service_center_id' => $request['service_center'][$key],
                'notes' => $request['notes'][$key],
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $ticketDetailData[] = $data;
        }

        DB::table('ticket_details')->insert($ticketDetailData);
        return DB::table('ticket_details')->orderBy("id", "asc")->where('ticket_id', $ticketId)->pluck('id')->toArray();
    }

    /**
     * @param $request
     * @param $ticketDetailIds
     * @return void
     */
    private function saveOrUpdateTicketCategoryProblem($request, $ticketDetailIds): void
    {
        $ticketDetailsWithProblem = [];
        if ($request->has('category_problem')) {
            foreach ($request['category_problem'] as $key => $problem) {
                $problemData = DB::table('product_category_problems')
                    ->whereIn('id', $problem)
                    ->select('id', 'name_bn', 'name')
                    ->get();

                foreach ($problemData as $problemRow) {
                    $data = [
                        'ticket_details_id' => $ticketDetailIds[$key],
                        'product_category_problem_id' => $problemRow->id,
                        'name' => $problemRow->name,
                        'name_bn' => $problemRow->name_bn ?? '',
                        'product_category_id' => $request['product_category'][$key]
                    ];
                    $ticketDetailsWithProblem[] = $data;
                }
            }
        }
        DB::table('ticket_detail_problems')->insert($ticketDetailsWithProblem);
    }


    private function generateTicketId(): int
    {
        // Generate a random 8-digit number
        return mt_rand(10000000, 99999999);
    }


    public function getCustomerDetails($phone): array
    {
        $customerInfo = [];
        if ($phone != null || $phone != '') {
            $customer = Customer::where('phone', $phone)->first();
            if ($customer) {
                $customerInfo = [
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email ?? '',
                    'address' => $customer->address,
                    'dist_id' => $customer->district_id,
                    'thana_id' => $customer->thana_id,
                    'additional_info' => [
                        'alternative_phone' => $customer->customer_details->alternative_phone ?? '',
                        'permanent_address' => $customer->customer_details->permanent_address ?? '',
                        'present_address' => $customer->customer_details->present_address ?? '',
                        'nid' => $customer->customer_details->nid ?? '',
                        'gender' => $customer->customer_details->gender ?? ''
                    ]
                ];
            }

        }
        return $customerInfo;
    }

    function updateTicket($request)
    {
        try {
            if ($request->has('tid')) {
                $tid = $request['tid'];
                $userName = $request['agentUserName'];
                $channel = Channel::where('channel_number', $request['channelNumber'])->first();
                $agent = User::where('username', $userName)->firstOrFail();
                if ($agent->role->name == 'Agent') {
                    $ticket = Ticket::where('channel_id', $channel->id)
                        ->where('id', $tid)
                        ->where('status', 'pending')
                        ->firstOrFail();
                    $ticketDetailsId = TicketDetail::where('ticket_id', $ticket->id)->pluck('id')->toArray();
                    DB::beginTransaction();
                    DB::table('ticket_detail_problems')->whereIn('ticket_details_id', $ticketDetailsId)->delete();
                    DB::table('ticket_details')->where('ticket_id', $ticket->id)->delete();
                    DB::commit();
                    return $this->createServiceTicket($request, $tid);
                } else {
                    return false; // Agent not found or not authorized
                }
            } else {
                return false; // Missing ticket ID
            }
        } catch (ModelNotFoundException $e) {
            return false; // Ticket or user not found
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage(); // Other exceptions
        }
    }


    function saveOrUpdateCustomerDetails($request): bool
    {
        $customer = Customer::where('phone', $request['callerId'])->first();
        $data = [
            'name' => $request['name'],
            'address' => $request['address'],
            'thana_id' => $request['thana_id'],
            'district_id' => $request['district_id']
        ];
        $customerDetail = [
            'alternative_phone' => $request['alternative_phone'] ?? null,
            'present_address' => $request['present_address'] ?? null,
            'permanent_address' => $request['permanent_address'] ?? null,
            'nid' => $request['nid'] ?? null,
            'gender' => $request['gender'] ?? null
        ];
        try {
            DB::beginTransaction();
            if ($customer) {
                $customer->update($data);
            } else {
                $data['phone'] = $request['callerId'];
                $customer = Customer::create($data);
            }
            $customerDetail['customer_id'] = $customer->id;

            if (DB::table('customer_details')->where('customer_id', $customer->id)->exists()) {
                DB::table('customer_details')->where('customer_id', $customer->id)->update($customerDetail);
            } else {
                DB::table('customer_details')->insert($customerDetail);
            }
            DB::commit();
            return true;
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th->getMessage());
        }

    }


    /**
     * Admin panel ticket filtering
     * @param $request
     */
    function admin_panel_data($request, bool $export = false)
    {
        $query = DB::table('tickets')
            ->join('customers','customers.id','tickets.customer_id')
            ->join('ticket_details','tickets.id','=','ticket_details.ticket_id')
            ->join("brands","ticket_details.brand_id","=","brands.id")
            ->join("ticket_detail_problems","ticket_detail_problems.ticket_details_id","=","ticket_details.id")
            ->join("product_categories","ticket_detail_problems.product_category_id","=","product_categories.id")
            ->join('users',"users.id",'=','tickets.user_id')
            ->join('channels','channels.id',"=",'tickets.channel_id')
            ->join('districts',"districts.id","=","tickets.district_id");

        if ($request->has('ticket_no') && $request['ticket_no']!='') {
            $query->where('tickets.ticket_no', 'LIKE', '%' . $request['ticket_no'] . '%');
        }
        if ($request->has('customer_phone') && $request['customer_phone'] != '') {
            $query->where('customers.phone', 'LIKE', '%' . $request['customer_phone'] . '%');
        }
        if ($request->has('status') && $request['status'] != '') {
            $query->where('tickets.status', $request['status']);
        }
        if ($request->has('channel') && $request['channel'] != '') {
            $query->where('channel_id', $request['channel']);
        }
        if ($request->has('district_id') && $request['district_id'] != '') {
            $query->where('tickets.district_id', $request['district_id']);
        }
        if ($request->has('complain_date') && $request['complain_date'] != '') {
            // Remove any potential BOM characters and trim whitespace
            $complainDate = preg_replace('/\x{FEFF}/u', '', $request['complain_date']);
            $complainDate = trim($complainDate);
            // Split the date range into start and end dates
            $dates = explode(' - ', $complainDate);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            // Apply the filter using whereBetween for the date range, comparing only the date part of created_at
            $query->whereRaw("DATE(tickets.created_at) BETWEEN ? AND ?", [$startDate, $endDate]);
        }
        if (auth('web')->check()) {
            $userId = auth('web')->user()->id;
            $user = User::findOrFail($userId);
            if ($user->user_group->id == 2) {
                $query->where('user_id', $user->id);
            }
            else if($user->user_group->id == 8){
                $query
                    ->where('ticket_details.service_center_id',33);
            }
        }
        $query->select(
            'tickets.id as id',
            'tickets.ticket_no as ticket_no',
            'customers.phone as customer_phone',
            'districts.name as district_name',
            'users.username as user_name',
            'channels.name as channel_name',
            'tickets.status as status',
            'tickets.created_at as created_at',
            'tickets.updated_at as updated_at',
            'ticket_details.status as ticket_details_status',
            "brands.name as brand_name",
            'product_categories.name as product_category_name'
        );

//        $dbData=$query->get();
//        $processedData=[];
//        foreach ($dbData as $data){
//            $processedData[$data->id]=$data["id"];
//            $processedData[$data->id]=$data["ticket_no"];
//            $processedData[$data->id]=$data["customer_phone"];
//            $processedData[$data->id]=$data["district_name"];
//            $processedData[$data->id]=$data["user_name"];
//            $processedData[$data->id]=$data["channel_name"];
//            $processedData[$data->id]=$data["status"];
//            $processedData[$data->id]["ticket_details"]=$data["ticket_details_status"];
//        }
//
//        return $dbData;


        if ($export) {
            return $query->get();
        }
        return $query->orderBy('tickets.id', "DESC");


    }


}
