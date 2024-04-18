<?php

namespace App\Services\Backend\ModuleServices\ProductModule\ProductCategory;

use App\Models\Backend\CommonModule\Brand;
use App\Models\Backend\CommonModule\ServiceCenter;
use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\UserModule\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ProductCategoryService
{

    public function getIndexData()
    {
        if (auth("web")->check()) {
            $userId = auth('web')->user()?->id;
            $user = User::find($userId);
            if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                $data = Brand::all();
            } else {
                $userBusinessUnit = User::with("business_unit")->find($userId)?->business_unit?->pluck("id")->toArray();
                $data = Brand::whereIn("business_unit_id", $userBusinessUnit)->get();
            }
        } else {
            $data = Brand::all();
        }
        return $data;
    }

    /**
     * @throws Throwable
     */
    public function createProductCategory($request): ProductCategory
    {
        try {
            DB::beginTransaction();
            $productCategory = new ProductCategory();
            $productCategory->name = $request['name'];
            $productCategory->is_active = $request['is_active'];
            $productCategory->is_home_service = isset($request['is_home_service']) && $request['is_home_service'] == 'on';
            $productCategory->save();

            // Handle relationships (brands, notes, problems)
            $this->handleBrandRelationship($productCategory, $request['brand_id']);
            if (count($request['note']) > 0) {
                $this->handleNoteRelationship($productCategory, $request['note']);
            }
            $this->handleProblemRelationship($productCategory, $request['problem_en'], $request['problem_bn']);
            $this->handelServiceCenterRelationship($productCategory, $request["service_center_id"]);

            DB::commit();
            return $productCategory;
        } catch (Throwable $th) {
            DB::rollBack();
            throw  new \Exception($th->getMessage());
        }
    }


    private function handelServiceCenterRelationship($product_category, $service_center_id): void
    {
        $product_category->service_center()->sync($service_center_id);
    }

    public function updateCategory($request, $id): bool
    {
        $productCategory = ProductCategory::find($id);
        if (!$productCategory) {
            return false; // or throw an exception if you want to handle this differently
        }
        $request->validated($request);
        $productCategory->name = $request->input('name');
        $productCategory->is_active = $request->input('is_active');
        $productCategory->is_home_service = $request['is_home_service'] == 'on';
        $productCategory->save();
        $brandIds = $request->input('brand_id');
        $this->handleBrandRelationship($productCategory, $brandIds);
        $this->handelServiceCenterRelationship($productCategory, $request["service_center_id"]);


        return true;
    }

    private function handleBrandRelationship($productCategory, $brandIds): void
    {
        $productCategory->brand()->sync($brandIds);
    }

    private function handleNoteRelationship($productCategory, $notes): void
    {
        $productNote = [];
        foreach ($notes as $key => $value) {
            if ($value != '') {
                $productNote[] = [
                    'note' => $value,
                    'is_active' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }
        if (!empty($productNote)) {
            $productCategory->product_category_note()->createMany($productNote);
        }

    }

    private function handleProblemRelationship($productCategory, $problems_en, $problem_bn): void
    {
        $product_problems = [];
        if (count($problems_en) > 0) {
            foreach ($problems_en as $key => $value) {
                $product_problems[] = [
                    'product_category_id' => $productCategory->id,
                    'name' => $value,
                    'name_bn' => $problem_bn[$key],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('product_category_problems')->insert($product_problems);
        }
    }


    public function bulk_upload($file, $bu_id)
    {
        if (($handle = fopen($file->getPathname(), "r")) !== false) {
            // Read the first row as headers
            $headers = fgetcsv($handle);

            $headers = array_map(function ($header) {
                return trim(str_replace("\xEF\xBB\xBF", '', $header));
            }, $headers);

            // Define expected headers
            $expectedHeaders = ['Brands', 'ProductCategoryName', 'Problems[EN]', 'Problems[BN]', 'Notes', 'IsHomeService'];
            $expectedHeaders = array_map('trim', $expectedHeaders); // Trim expected headers
            $brands = DB::table('brands')->where("business_unit_id", $bu_id)->pluck('id', 'name')->toArray();

            // dd(array_intersect($expectedHeaders, $headers));
            // Validate headers
            if (count(array_intersect($expectedHeaders, $headers)) == count($expectedHeaders)) {
                // Headers are valid, proceed with processing the CSV data
                $data_array = [];
                while (($row = fgetcsv($handle)) !== false) {

                    $rowData = array_combine($headers, $row);
                    if (array_key_exists(trim($rowData['Brands']), $brands)) {
                        $data_array[$brands[trim($rowData['Brands'])]][trim($rowData['ProductCategoryName'])]['problem_en'] = $rowData['Problems[EN]'];
                        $data_array[$brands[trim($rowData['Brands'])]][trim($rowData['ProductCategoryName'])]['problem_bn'] = $rowData['Problems[BN]'];
                        $data_array[$brands[trim($rowData['Brands'])]][trim($rowData['ProductCategoryName'])]['notes'] = $rowData['Notes'];
                        $data_array[$brands[trim($rowData['Brands'])]][trim($rowData['ProductCategoryName'])]['is_home_service'] = $rowData['IsHomeService'];
                    }


                }
                fclose($handle);
                if (empty($data_array)) {
                    return [
                        'status' => "error",
                        "message" => "Data already exists"
                    ];
                }

                try {
                    $productBrand = [];
                    $productProblem = [];
                    $productProblemNote = [];
                    DB::beginTransaction();
                    foreach ($data_array as $key => $data) {
                        foreach ($data as $name => $datum) {
                            $existingCategoryName = DB::table('product_categories')->where('name', trim($name));
                            if (!$existingCategoryName->exists()) {
                                $data = [
                                    'name' => trim($name),
                                    'is_active' => true,
                                    'is_home_service' => strtolower($datum['is_home_service']) === 'yes' || $datum['is_home_service'] == '',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now()
                                ];
                                $category_id = DB::table('product_categories')->insertGetId($data);
                                $productProblemNote[] = [
                                    'product_category_id' => $category_id,
                                    'note' => $datum['notes'],
                                    'is_active' => true,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now()
                                ];
                                $problem_en = array_map('trim', explode(',', $datum['problem_en']));
                                $problem_bn = array_map('trim', explode(',', $datum['problem_bn']));
                                foreach ($problem_en as $index => $problem) {
                                    $productProblem[] = [
                                        'name' => $problem,
                                        'name_bn' => $problem_bn[$index] ?? '',
                                        'product_category_id' => $category_id,
                                        'is_active' => true,
                                    ];
                                }
                                $productBrand[] = [
                                    'product_category_id' => $category_id,
                                    'brand_id' => $key,
                                ];
                            } else {
                                $existingCategory = $existingCategoryName->select('id')->first();
                                $productBrand[] = [
                                    'product_category_id' => $existingCategory->id,
                                    'brand_id' => $key,
                                ];
                            }


                        }
                    }
                    DB::table('brand_product_category')->insert($productBrand);
                    DB::table('product_category_problems')->insert($productProblem);
                    DB::table('product_category_notes')->insert($productProblemNote);
                    DB::commit();
                    return [
                        'status' => "success",
                        "message" => "File uploaded successfully"
                    ];
                } catch (\Exception $th) {
                    DB::rollBack();
                    return [
                        'status' => "error",
                        "message" => $th->getMessage()
                    ];
                }
            } else {
                fclose($handle); // Close the file handle
                return [
                    'status' => "error",
                    "message" => "Invalid header"
                ];
            }
        } else {
            return [
                'status' => "error",
                "message" => "Failed to open file"
            ];
        }
    }


    public function getUserBasedServiceCenterList()
    {
        if (auth("web")->check()) {
            $userId = auth("web")->user()->id;
            $user = User::find($userId);
            if ($user->user_group->id == 1 && count(isAdminBusinessUnitAccess()) == 0) {
                return $service_centers = ServiceCenter::all();
            }
            return $service_centers = ServiceCenter::join('business_unit_service_center', 'service_centers.id', '=', 'business_unit_service_center.service_center_id')
                ->join('business_unit_user', 'business_unit_service_center.business_unit_id', '=', 'business_unit_user.business_unit_id')
                ->where('business_unit_user.user_id', $user->id)
                ->select('service_centers.*')
                ->distinct()
                ->get();

        } else {
            return $service_centers = ServiceCenter::all();
        }
    }

    public function getUserBasedBrands()
    {

    }

}
