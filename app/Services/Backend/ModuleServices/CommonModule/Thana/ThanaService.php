<?php

namespace App\Services\Backend\ModuleServices\CommonModule\Thana;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ThanaService
{
    function uploadBulk($file)
    {
        if (($handle = fopen($file->getPathname(), "r")) !== false) {
            // Read the first row as headers
            $headers = fgetcsv($handle);
            $headers = array_map(function ($header) {
                return trim(str_replace("\xEF\xBB\xBF", '', $header));
            }, $headers);

            // Define expected headers
            $expectedHeaders = ['District', 'Thana'];
            $expectedHeaders = array_map('trim', $expectedHeaders); // Trim expected headers
            $districts = DB::table('districts')->select('id', 'name')->get();
            $thanas=DB::table('thanas')->select('name','district_id')->get();
            // Validate headers
            if (count(array_intersect($expectedHeaders, $headers)) == count($expectedHeaders)) {
                // Headers are valid, proceed with processing the CSV data
                $data_array = [];
                while (($row = fgetcsv($handle)) !== false) {
                    $rowData = array_combine($headers, $row);
                    $data_array[$rowData['District']][] = strtolower(trim($rowData['Thana']));
                }
                $thanasCollection = collect($thanas);
                fclose($handle);
                $uniqueDataArray=array_map('array_unique',$data_array);
                $distThana = [];
                foreach ($districts as $district) {
                    $districtName = $district->name;
                    if (array_key_exists($districtName, $uniqueDataArray)) {
                        foreach ($uniqueDataArray[$districtName] as $item) {
                            $existingThana = $thanasCollection->first(function ($thana) use ($item, $district) {
                                return strtolower($thana->name) === strtolower($item) && $thana->district_id === $district->id;
                            });
                            if (!$existingThana) {
                                $distThana[] = [
                                    'name' => ucwords($item),
                                    'district_id' => $district->id,
                                    'is_active' => true,
                                     'created_at' => Carbon::now(),
                                     'updated_at' => Carbon::now()
                                ];
                            }
                        }
                    }
                }
                if(empty($distThana)){
                    return  [
                      'status'=>'error',
                      'message'=>"File content already exists"
                    ];
                }
                $data=collect($distThana);
                DB::table('thanas')->insert($distThana);
                return  [
                    'status'=>'success',
                    'message'=>"File upload successful"
                ];
            } else {
                fclose($handle); // Close the file handle
                return  [
                    'status'=>'error',
                    'message'=>"CSV file not contain proper header"
                ];
            }
        } else {
            return  [
                'status'=>'error',
                'message'=>"Something went wrong"
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
