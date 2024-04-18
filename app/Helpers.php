<?php

use App\Models\Backend\UserModule\Module;
use App\Models\Backend\UserModule\User;

function can($can)
{
    if (auth('web')->check()) {
        if (!isset(auth('web')->user()->role->permission)) {
            return false;
        }
        foreach (auth('web')->user()->role->permission as $permission) {
            if ($permission->key == $can) {
                return true;
            }
        }
        return false;
    }
    return back();
}

//check user access permission function end

//unauthorized text start
function unauthorized()
{
    return "You are not authorized for this request";
}

//unauthorized text end

//mail from start
function mail_from()
{
    return "test@sehirulislamrehi.com";
}

//mail from end

function mobileNumberValidate($mobile)
{

    if (strlen($mobile) > 11) {
        $mobile = substr($mobile, -11);
    }
    return $mobile;
}

/**
 * Return the active class for styling for module if it's sub module is activne
 *
 * @param Module $module
 * @param string $currentRouteName
 * @return string
 */
function get_sub_module(Module $module, $currentRouteName): string
{
    $submodules = $module->sub_module()->pluck('route')->toArray();
    if (in_array($currentRouteName, $submodules)) {
        return 'active';
    } else {
        return '';
    }
}

function get_active_module(Module $module, $currentRouteName): string
{
    $submodules = $module->sub_module()->pluck('route')->toArray();
    if (in_array($currentRouteName, $submodules)) {
        return 'menu-open';
    } else {
        return '';
    }
}


function entity_folder_mapping(string $entity_name)
{
    $entity_map = [
        'channel' => 'channel_logo'
    ];

    if (array_key_exists($entity_name, $entity_map)) {
        return $entity_map[$entity_name];
    }

    return null;
}

function get_base_path($file_name): string
{
    $folder_location = entity_folder_mapping('channel');
    return 'storage/' . $folder_location . '/' . $file_name;
}


function translateNumberToBengali($number): string
{
    $bengaliNumerals = [
        '০' => 0,
        '১' => 1,
        '২' => 2,
        '৩' => 3,
        '৪' => 4,
        '৫' => 5,
        '৬' => 6,
        '৭' => 7,
        '৮' => 8,
        '৯' => 9
    ];

    $numberStr = (string)$number;
    $bengaliNumber = '';

    for ($i = 0; $i < strlen($numberStr); $i++) {
        $digit = $numberStr[$i];
        if (isset($bengaliNumerals[$digit])) {
            $bengaliNumber .= $bengaliNumerals[$digit];
        } else {
            $bengaliNumber .= $digit;
        }
    }

    return $bengaliNumber;
}


function checkUserGroup(string $groupName): bool
{
    if (auth('web')->check()) {
        $currentUser = auth('web')->user();
        $userId = $currentUser->id ?? null;
        if (!is_null($userId)) {
            $userGroup = \App\Models\Backend\UserModule\User::find($userId)->user_group->name;
            if (strtolower($groupName) === strtolower($userGroup)) {
                return true;
            }
        }
        return false;
    } elseif (auth('super_admin')->check()) {
        return true;
    }
    return false;
}


function isAdminBusinessUnitAccess(): array
{
    $userId = auth("web")->user()->id;
    return \Illuminate\Support\Facades\DB::table("business_unit_user")
        ->where("user_id",$userId)
        ->pluck("business_unit_id")
        ->toArray();
}


