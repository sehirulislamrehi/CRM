<?php

namespace App\Services\Backend\ModuleServices\CommonModule\Brand;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BrandService
{

    public function bulk_upload($file, $bu_id)
    {
        if (($handle = fopen($file->getPathname(), "r")) !== false) {
            // Read the first row as headers
            $headers = fgetcsv($handle);

            $headers = array_map(function ($header) {
                return trim(str_replace("\xEF\xBB\xBF", '', $header));
            }, $headers);

            // Define expected headers
            $expectedHeaders = ['ProductCategoryName', 'Brands'];
            $expectedHeaders = array_map('trim', $expectedHeaders); // Trim expected headers
            // Validate headers
            if (count(array_intersect($expectedHeaders, $headers)) == count($expectedHeaders)) {
                // Headers are valid, proceed with processing the CSV data
                $data_array = [];
                while (($row = fgetcsv($handle)) !== false) {

                    $rowData = array_combine($headers, $row);
                    $data_array[] = $rowData['Brands'];

                }
                fclose($handle);
                $brands = $this->uniqueMultiKeyArray($data_array);
                $brandData = [];
                foreach ($brands as $brand) {
                    $brandData[] = [
                        'name' => trim($brand),
                        'business_unit_id' => $bu_id,
                        'is_active' => true,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
                try {
                    DB::beginTransaction();
                    DB::table('brands')->insert($brandData);
                    DB::commit();
                    return [
                        "status" => "success",
                        "message" => "File upload successful"
                    ];
                } catch (\Exception $th) {
                    DB::rollBack();
                    return [
                        "status" => "error",
                        "message" => $th->getMessage()
                    ];
                }
            } else {
                fclose($handle); // Close the file handle
                return [
                    "status" => "error",
                    "message" => "Invalid header"
                ];
            }
        } else {
            return [
                "status" => "error",
                "message" => "Failed to open file"
            ];
        }
    }


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
}
