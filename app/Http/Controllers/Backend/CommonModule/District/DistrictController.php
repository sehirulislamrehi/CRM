<?php

namespace App\Http\Controllers\Backend\CommonModule\District;

use App\Http\Controllers\Controller;
use App\Models\Backend\CommonModule\District;
use App\Services\Backend\DatatableServices\CommonModule\District\DistrictDataTableService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DistrictController extends Controller
{
    protected DistrictDataTableService $districtDataTableService;

    /**
     * @param DistrictDataTableService $districtDataTableService
     */
    public function __construct(DistrictDataTableService $districtDataTableService)
    {
        $this->districtDataTableService = $districtDataTableService;
    }


    function index():View
    {
        if (can('district_index')) {
            return view("backend.modules.common_module.district.index");
        }
        return view('error.403');
    }

    function data(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        if (can('district_index')) {
            $data = District::query();
            return $this->districtDataTableService->getDistrictData($data);

        }
        return view('error.403');
    }

    function details($id):View
    {
        if (can('district_index')) {
            $district = District::find($id);
            if ($district) {
                return view("backend.modules.common_module.district.modals.edit", compact("district"));
            }
        }
        return view('error.modals.403');

    }

    function create_modal()
    {
        if (can('district_index')) {
            return view('backend.modules.common_module.district.modals.create');
        }
        return view('error.modals.403');

    }

    function create(Request $request)
    {
        if (can('district_index')) {
            $request->validate([
                'name' => 'required',
                'is_active' => 'required'
            ]);
            try {
                $district = new District();
                $district->name = $request->input('name');
                $district->is_active = $request->input('is_active');
                $status = $district->save();
                if ($status) {
                    return response()->json([
                        'message' => 'Successfully updated',
                        'alertType' => 'success'
                    ]);
                } else {
                    return response()->json([
                        'message' => 'something went wrong',
                        'alertType' => 'error'
                    ]);
                }
            } catch (\Exception $th) {
                return response()->json([
                    'message' => 'something went wrong',
                    'alertType' => 'error'
                ]);
            }
        }
    }

    function update(Request $request, $id)
    {
        if (can('district_index')) {
            $district = District::find($id);
            if ($district) {
                $request->validate([
                    "is_active" => "required"
                ]);
                try {
                    $district->is_active = $request->input("is_active");
                    $status = $district->save();
                    if ($status) {
                        return response()->json([
                            'message' => 'Successfully updated',
                            'alertType' => 'success'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'something went wrong',
                            'alertType' => 'error'
                        ]);
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        'message' => 'something went wrong',
                        'alertType' => 'error'
                    ]);
                }
            }
        }
        return view('error.403');
    }
}
