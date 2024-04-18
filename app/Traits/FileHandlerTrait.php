<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Handel File upload and delete request 
 * @package App\Traits;
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
trait FileHandlerTrait
{
    /**
     * Upload file to destination folder
     *
     * @param UploadedFile $file
     * @param string $store_location
     * @param string $name
     * @return bool
     */
    protected function upload_file(UploadedFile $file, string $store_location, string $name):bool
    {
        try {
            $file->storeAs($store_location, $name, 'public');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }



    protected function remove_file($fullPathOfExistingFile):bool
    {
        try {
            if (Storage::exists('public/'.$fullPathOfExistingFile)) {
                Storage::delete('public/'.$fullPathOfExistingFile);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
