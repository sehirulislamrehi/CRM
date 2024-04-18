<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    public function changeLocale(Request $request)
    {
        $locale = $request->input('locale');
        session(['locale' => $locale]);
        return response()->json(['success' => true]);
    }
}
