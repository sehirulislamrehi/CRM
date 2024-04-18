<?php

namespace App\Services\Backend\ModuleServices\CommonModule\ServiceCenter;

use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\CommonModule\ServiceCenter;
use App\Models\Backend\CommonModule\Thana;
use App\Models\Backend\UserModule\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * ServiceCenterService - Service class for managing ServiceCenter entities.
 *
 * This service class provides methods for creating, updating, and managing ServiceCenter entities,
 * including handling relationships with Thana entities, csv bulk upload etc.
 *
 * @package App\Services\Backend\ModuleServices\CommonModule\ServiceCenter
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ServiceCenterService
{


    public function getBusinessUnit()
    {
        if (auth("web")->check()) {
            $userId = auth("web")->user()->id;
            $user = User::with("business_unit")->find($userId);
            if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                $data = BusinessUnit::where("is_active", true)->get();
            } else {
                $data = $user->business_unit;
            }

        } else {
            $data = BusinessUnit::where("is_active", true)->get();
        }
        return $data;
    }


    public function createServiceCenter($request): void
    {
        $service_center = new ServiceCenter();
        $service_center->name = $request->input('name');
        $service_center->address = $request->input('address');
        $service_center->is_active = $request->input('is_active');
        $service_center->save();
        if (count($request->input('thana_id')) > 0) {
            $this->handelThanaRelationship($service_center, $request->input('thana_id'));
        }
        $this->handelBusinessUnitRelationship($service_center,$request->input("business_unit_id"));
    }

    /**
     * @param $request
     * @param $id
     * @return void
     */
    public function updateServiceCenter($request, $id): void
    {
        $service_center = ServiceCenter::find($id);
        if ($service_center) {
            $service_center->name = $request->input("name");
            $service_center->address = $request->input('address');
            $service_center->is_active = $request->input("is_active");
            $service_center->latitude = $request->input("latitude");
            $service_center->longitude = $request->input("longitude");
            $service_center->save();
            if (count($request->input('thana_id')) > 0) {
                $this->handelThanaRelationship($service_center, $request->input('thana_id'));
            }
            $this->handelBusinessUnitRelationship($service_center,$request->input("business_unit_id"));

        }
    }

    public function handelThanaRelationship($serviceCenter, $thana_id): void
    {
        $serviceCenter->thana()->sync($thana_id);
    }

    public function handelBusinessUnitRelationship($serviceCenter, $business_unit_id): void
    {
        $serviceCenter->business_unit()->sync($business_unit_id);
    }

    public function serviceCenterWithThana($thana_id)
    {
        $thana = Thana::with('service_center')->findOrFail($thana_id);
        return $thana->service_center;
    }

    public function getSelectedBusinessUnit(int $id): array
    {
        return ServiceCenter::with("business_unit")->find($id)->business_unit->pluck("id")->toArray();
    }

    /**
     * Pre-process csv data.
     *!!!! Critical Method. Proceed with caution !!!!
     * @param $handle
     * @param $headers
     * @param $hashedPass
     * @param $districts
     * @return array
     */
    private function csvPreprocess(&$handle, &$headers, $hashedPass, &$districts): array
    {
        $data_array = [];
        while (($row = fgetcsv($handle)) !== false) {

            $rowData = array_combine($headers, $row);

            $districtName = $rowData['District'];

            if (isset($districts[$districtName])) {

                $data_array[$rowData['Service Center Name']]['district'][] = $districts[$districtName];

                $data_array[$rowData['Service Center Name']]['service_center_details']['name'] = $rowData['Service Center Name'];

                $data_array[$rowData['Service Center Name']]['service_center_details']['address'] = $rowData['District'] . ', ' . $rowData['Thana'];

                $data_array[$rowData['Service Center Name']]['service_center_details']['phone'] = $rowData['SPI Number'];

                $data_array[$rowData['Service Center Name']]['service_center_details']['is_active'] = true;

                $data_array[$rowData['Service Center Name']]['product_category'][] = $rowData['Product Category'];

                $data_array[$rowData['Service Center Name']]['service_center_details']['created_at'] = now();

                $data_array[$rowData['Service Center Name']]['service_center_details']['updated_at'] = now();

                $data_array[$rowData['Service Center Name']]['thana'][$rowData['Thana']] = $rowData['District'];

                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["username"] = strtolower(str_replace([',', ' ', '-', '.'], '', $rowData['SPI Name']) . substr($rowData['SPI Number'], -4));

                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["fullname"] = $rowData['SPI Name'];

                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["phone"] = $rowData['SPI Number'];

                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["email"] = $rowData['SPI Email'] ?? '';

                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["password"] = $hashedPass;

                //technician group id don't change the id. changing id could lead to unwanted application behavior
                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["user_group_id"] = 8;

                //Role id for service center don't change the id. Changing id could lead to unwanted application behavior, If change you need to change on role seeder.
                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["role_id"] = 3;

                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["is_active"] = true;

                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["created_at"] = now();

                $data_array[$rowData['Service Center Name']]['spi'][$rowData['SPI Number']]["updated_at"] = now();

                if (isset($rowData['Technician Phone']) && $rowData['Technician Phone']) {

                    $data_array[$rowData['Service Center Name']]['technician'][$rowData['Technician Phone']]['fullname'] = $rowData['Technician Name'];

                    $data_array[$rowData['Service Center Name']]['technician'][$rowData['Technician Phone']]['username'] = strtolower(str_replace([',', ' ', '-', '.'], '', $rowData['Technician Name']) . substr($rowData['Technician Phone'], -4)).rand(1000,9999);

                    $data_array[$rowData['Service Center Name']]['technician'][$rowData['Technician Phone']]['category'] = $rowData['Product Category'];
                }
            }

        }
        return $data_array;
    }


    /**
     * @param $data_array
     * @param $existingProductCategory
     * @param $hashedPass
     * @param $categoryServiceCenter
     * @param $userData
     * @param $technicianServiceCenter
     * @param $technicianDataArray
     * @param $technicianCategory
     * @param $bu_id
     * @param $serviceCenterThana
     * @param $businessUnitServiceCenter
     * @return void
     */
    private function serviceCenterProcess(&$data_array, &$existingProductCategory, $hashedPass, &$categoryServiceCenter, &$userData, &$technicianServiceCenter, &$technicianDataArray, &$technicianCategory, $bu_id, &$serviceCenterThana, &$businessUnitServiceCenter): void
    {
        $existingServiceCenterBusinessUnitMapping = DB::table('business_unit_service_center')->get();
        $existingServiceCenterBusinessUnitMapping = collect($existingServiceCenterBusinessUnitMapping);
        foreach ($data_array as $data) {

            $serviceCenterId = DB::table('service_centers')->insertGetId($data['service_center_details']);

            $uniqueDistrictId = array_unique($data['district']);

            //Processing product category
            $trimProductCategory = array_unique(array_map('trim', $data['product_category']));

            $flattenedArray = array_reduce($trimProductCategory, function ($carry, $item) {
                // Explode each item by comma and trim spaces
                $items = array_map('trim', explode(',', $item));
                // Merge with the carry array`
                return array_merge($carry, $items);
            }, []);


            $uniqueArray = array_map(function ($item) {
                return str_replace("\u{A0}", "", $item);
            }, $flattenedArray);

            $categoryArray = array_values(array_unique($uniqueArray));
            $categoryArray = array_map('trim', $categoryArray);
            $categoryArray = array_filter($categoryArray, function($value) {
                return strtolower($value) !== 'all';
            });
            //End processing product category

            $categoryId = $existingProductCategory->whereIn('name', $categoryArray)->pluck('id')->toArray();
            foreach ($categoryId as $id) {
                $categoryServiceCenter[] = [
                    'product_category_id' => $id,
                    'service_center_id' => $serviceCenterId,
                ];
            }

            $thanaIds = DB::table('thanas')->whereIn('name', array_keys($data['thana']))->whereIn('district_id', $uniqueDistrictId)->pluck('id')->toArray();
            foreach ($data['spi'] as $key => $user) {
                $userData[$key] = $user;
            }

            if (isset($data['technician'])) {
                foreach ($data['technician'] as $technicianPhone => $technicianData) {
                    $technicianServiceCenter[$this->formatPhoneNumber($technicianPhone)]['service_center_id'] = $serviceCenterId;
                    $technicianDataArray[] = [
                        'fullname' => $technicianData['fullname'],
                        'phone' => $this->formatPhoneNumber($technicianPhone),
                        'username' => $technicianData['username'],
                        //Role id for service center don't change the id. Changing id could lead to unwanted application behavior, If change you need to change on role seeder.
                        'role_id'=>4,
                        //technician group id don't change the id. changing id could lead to unwanted application behavior
                        'user_group_id' => 9,
                        'is_active' => true,
                        'password' => $hashedPass,
                    ];
                    $technicianCategory[$this->formatPhoneNumber($technicianPhone)] = array_map('trim', explode(',', $technicianData['category']));
                }
            }


            foreach ($thanaIds as $thanaId) {
                $serviceCenterThana[] = [
                    'service_center_id' => $serviceCenterId,
                    'thana_id' => $thanaId
                ];

            }

            if ($existingServiceCenterBusinessUnitMapping->where('business_unit_id', $bu_id)->where('service_center_id', $serviceCenterId)->isEmpty()) {
                $businessUnitServiceCenter[] = [
                    'business_unit_id' => $bu_id,
                    'service_center_id' => $serviceCenterId,
                ];
            }
        }

    }

    /**
     * !!!! Critical Method. Proceed with caution !!!!
     * @param $userData
     * @param $existingServiceCenterUserMapping
     * @param $serviceCenterUser
     * @param $existingBusinessUnitUserMapping
     * @param $bu_id
     * @param $businessUnitUser
     * @return void
     */
    private function processUserData(&$userData, &$existingServiceCenterUserMapping, &$serviceCenterUser, &$existingBusinessUnitUserMapping, $bu_id, &$businessUnitUser): void
    {
        $existedUser = DB::table('users')->where('user_group_id', 8)->get();
        $existedUser = collect($existedUser);
        foreach ($userData as $key => $userItem) {

            $serviceCenterIds = DB::table('service_centers')->where('phone', $key)->pluck('id')->toArray();

            $is_user_exists = $existedUser->where('phone', $userItem['phone'])->first();

            if ($is_user_exists) {

                $userId = $is_user_exists->id;

            } else {
                $userId = DB::table('users')->insertGetId($userItem);
            }


            foreach ($serviceCenterIds as $id) {
                if ($existingServiceCenterUserMapping->where('service_center_id', $id)->where('user_id', $userId)->isEmpty()) {

                    $serviceCenterUser[] = [
                        'service_center_id' => $id,
                        'user_id' => $userId,
                    ];
                }
                if ($existingBusinessUnitUserMapping->where('business_unit_id', $bu_id)->where('user_id', $userId)->isEmpty()) {

                    $businessUnitUser[] = [
                        'business_unit_id' => $bu_id,
                        'user_id' => $userId,
                    ];
                }
            }
        }
    }

    /**
     * !!!! Critical Method. Proceed with caution !!!!
     * @param $technicianDataArray
     * @param $existingProductCategory
     * @param $technicianCategoryMapping
     * @param $technicianServiceCenter
     * @param $existingServiceCenterUserMapping
     * @param $existingBusinessUnitUserMapping
     * @param $bu_id
     * @param $serviceCenterUser
     * @param $businessUnitUser
     * @return void
     */
    private function processTechnicianData(&$technicianDataArray, &$existingProductCategory, &$technicianCategoryMapping, &$technicianServiceCenter, &$existingServiceCenterUserMapping, &$existingBusinessUnitUserMapping, $bu_id, &$serviceCenterUser, &$businessUnitUser): void
    {
        $existingTechnician = DB::table('users')->where('user_group_id', 9)->get();
        $existingTechnician = collect($existingTechnician);
        foreach ($technicianDataArray as $key => $technician) {

            $is_technician_exists = $existingTechnician->where('phone', $technician['phone'])->first();


            $technicianHasCategory = [];
            if ($is_technician_exists) {

                $technicianId = $is_technician_exists->id;

            } else {
                $technicianId = DB::table('users')->insertGetId($technician);
            }

            if (isset($technicianCategory[$technician['phone']])) {
                $technicianHasCategory = $existingProductCategory->whereIn('name', $technicianCategory[$technician['phone']])->pluck('id')->toArray();
            }
            if (count($technicianHasCategory) > 0) {
                foreach ($technicianHasCategory as $technicianCategoryId) {
                    $technicianCategoryMapping[] = [
                        'product_category_id' => $technicianCategoryId,
                        'user_id' => $technicianId
                    ];
                }
            }
            $serviceCenterId = $technicianServiceCenter[$technician['phone']]['service_center_id'];
            if ($existingServiceCenterUserMapping->where('service_center_id', $serviceCenterId)->where('user_id', $technicianId)->isEmpty()) {
                $serviceCenterUser[] = [
                    'service_center_id' => $serviceCenterId,
                    'user_id' => $technicianId,
                ];
            }
            if ($existingBusinessUnitUserMapping->where('business_unit_id', $bu_id)->where('user_id', $technicianId)->isEmpty()) {

                $businessUnitUser[] = [
                    'business_unit_id' => $bu_id,
                    'user_id' => $technicianId,
                ];
            }

        }

    }

    /**
     * Handel Bulk upload option
     * !!!! Critical Method. Proceed with caution !!!!
     * @param $file
     * @param int $bu_id
     * @return array
     */
    public function uploadBulk($file, int $bu_id): array
    {
        if (($handle = fopen($file->getPathname(), "r")) !== false) {
            // Read the first row as headers
            $headers = fgetcsv($handle);

            $headers = array_map(function ($header) {
                return trim(str_replace("\xEF\xBB\xBF", '', $header));
            }, $headers);

            // Define expected headers
            $expectedHeaders = ['District', 'Thana', 'Product Category', 'Service Center Name', 'SPI Name', 'SPI Number'];
            $expectedHeaders = array_map('trim', $expectedHeaders); // Trim expected headers
            $districts = DB::table('districts')->pluck('name', 'id')->toArray();
            $districts = array_flip($districts);
            // Validate headers
            if (count(array_intersect($expectedHeaders, $headers)) == count($expectedHeaders)) {
                // Headers are valid, proceed with processing the CSV data
                $data_array = [];
                $hashedPass = Hash::make('123456');
                $data_array = $this->csvPreprocess($handle, $headers, $hashedPass, $districts);
//                dd($data_array);
                fclose($handle);
                //Handling database transaction
                try {
                    $existingProductCategory = DB::table('product_categories')->select('id', 'name')->get();
                    $existingProductCategory = collect($existingProductCategory);

                    DB::beginTransaction();

                    $userData = [];
                    $serviceCenterThana = [];
                    $businessUnitServiceCenter = [];
                    $technicianDataArray = [];
                    $technicianServiceCenter = [];
                    $categoryServiceCenter = [];
                    $technicianCategory = [];

                    //Processing Service center and additional technician and user
                    $this->serviceCenterProcess(
                        $data_array,
                        $existingProductCategory,
                        $hashedPass,
                        $categoryServiceCenter,
                        $userData,
                        $technicianServiceCenter,
                        $technicianDataArray,
                        $technicianCategory,
                        $bu_id,
                        $serviceCenterThana,
                        $businessUnitServiceCenter,
                    );

                    $serviceCenterUser = [];
                    $businessUnitUser = [];
                    $existingServiceCenterUserMapping = DB::table('service_center_user')->get();
                    $existingBusinessUnitUserMapping = DB::table('business_unit_user')->get();
                    $existingServiceCenterUserMapping = collect($existingServiceCenterUserMapping);
                    $existingBusinessUnitUserMapping = collect($existingBusinessUnitUserMapping);


                    //Inserting user data

                    $this->processUserData($userData,
                        $existingServiceCenterUserMapping,
                        $serviceCenterUser,
                        $existingBusinessUnitUserMapping,
                        $bu_id,
                        $businessUnitUser);

                    $technicianCategoryMapping = [];

                    //Inserting technician data

                    $this->processTechnicianData($technicianDataArray,
                        $existingProductCategory,
                        $technicianCategoryMapping,
                        $technicianServiceCenter,
                        $existingServiceCenterUserMapping,
                        $existingBusinessUnitUserMapping,
                        $bu_id,
                        $serviceCenterUser,
                        $businessUnitUser);

                    DB::table('product_category_service_center')->insert($categoryServiceCenter);

                    DB::table('service_center_user')->insert($this->uniqueMultiKeyArray($serviceCenterUser));

                    DB::table('service_center_thana')->insert($this->uniqueMultiKeyArray($serviceCenterThana));

                    DB::table('business_unit_service_center')->insert($this->uniqueMultiKeyArray($businessUnitServiceCenter));

                    DB::table('business_unit_user')->insert($this->uniqueMultiKeyArray($businessUnitUser));

                    DB::table('product_category_user')->insert($technicianCategoryMapping);

                    DB::commit();

                    return [
                        'status'=>'success',
                        'message'=>'CSV upload successfully'
                    ];

                } catch (\Exception $th) {
                    DB::rollBack();
                    return [
                        'status'=>'error',
                        'message'=>$th->getMessage()
                    ];
                }
            } else {
                fclose($handle); // Close the file handle
                return [
                    'status'=>'error',
                    'message'=>'Invalid CSV'
                ];
            }
        } else {
            return [
                'status'=>'error',
                'message'=>'Failed to open file'
            ];
        }
    }

    /**
     * @param $array
     * @return array
     */
    function uniqueMultiKeyArray($array): array
    {
        // Custom function to serialize and return array
        $customSerialize = function ($arr) {
            return serialize($arr);
        };

        // Map the customSerialize function to the array
        $serializedArray = array_map($customSerialize, $array);

        // Use array_unique to remove duplicates
        $uniqueSerializedArray = array_unique($serializedArray);

        // Map back to original format
        return array_map('unserialize', $uniqueSerializedArray);
    }

    /**
     * For phone number to bd format
     * @param $phoneNumber
     * @return array|string|null
     */
    private function formatPhoneNumber($phoneNumber): array|string|null
    {
        // Remove non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (strlen($phoneNumber) == 11 && substr($phoneNumber, 0, 2) === '01') {
            $phoneNumber = '880' . substr($phoneNumber, 1);
        }
        return $phoneNumber;
    }


}
