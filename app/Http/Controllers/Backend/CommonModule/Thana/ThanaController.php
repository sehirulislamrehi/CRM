<?php

namespace App\Http\Controllers\Backend\CommonModule\Thana;

use App\Http\Controllers\Controller;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\CommonModule\District;
use App\Models\Backend\CommonModule\Thana;
use App\Services\Backend\DatatableServices\CommonModule\Thana\ThanaDatatableService;
use App\Services\Backend\ModuleServices\CommonModule\Thana\ThanaService;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ThanaController extends Controller
{
    protected ThanaDatatableService $thanaDatatableService;
    protected ThanaService $thanaService;

    /**
     * @param ThanaDatatableService $thanaDatatableService
     * @param ThanaService $thanaService
     */
    public function __construct(ThanaDatatableService $thanaDatatableService, ThanaService $thanaService)
    {
        $this->thanaDatatableService = $thanaDatatableService;
        $this->thanaService = $thanaService;
    }


    function index(): View
    {
        if (can('thana_index')) {
            return view('backend.modules.common_module.thana.index');
        }
        return view('error.403');
    }

    function data(): \Illuminate\Http\JsonResponse
    {
        if (can('thana_index')) {
            $data = Thana::query();
            return $this->thanaDatatableService->getThanaData($data);
        }
        return response()->json([
            'message' => 'Unauthorized',
            'alertType' => 'error'
        ]);
    }

    function create_modal()
    {
        if (can('thana_index')) {
            $districts = District::all()->where('is_active', true);
            return view('backend.modules.common_module.thana.modals.create', compact('districts'));
        }
        return view('error.modals.403');
    }

    function create(Request $request): \Illuminate\Http\JsonResponse
    {
        if (can('thana_index')) {
            $request->validate([
                'name' => 'required',
                'district_id' => 'required|exists:districts,id'
            ]);
            try {
                $thana = new Thana();
                $thana->name = $request->input('name');
                $thana->district_id = $request->input('district_id');
                $thana->is_active = $request->input('is_active');
                if ($thana->save()) {
                    return response()->json(
                        [
                            'message' => 'Successfully created',
                            'alertType' => 'success'
                        ]
                    );
                } else {
                    return response()->json([
                        'message' => 'Failed create',
                        'alertType' => 'error'
                    ]);
                }
            } catch (\Exception $th) {
                return response()->json([
                    'message' => $th->getMessage(),
                    'alertType' => 'error'
                ]);
            }
        }
        return response()->json([
            'message' => 'Unauthorized',
            'alertType' => 'error'
        ]);
    }

    function update_modal($id): View
    {
        if (can('thana_index')) {
            $thana = Thana::find($id);
            $districts = District::all();
            if ($thana) {
                return view("backend.modules.common_module.thana.modals.edit", compact("thana", "districts"));
            }
        }
        return view('error.modals.403');
    }

    function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (can('thana_index')) {
            $thana = Thana::find($id);
            if ($thana) {
                $request->validate([
                    "is_active" => "required",
                    "district_id" => 'required'
                ]);
                try {
                    $thana->district_id = $request->input('district_id');
                    $thana->is_active = $request->input("is_active");
                    $status = $thana->save();
                    if ($status) {
                        return response()->json(
                            [
                                'message' => 'Successfully updated',
                                'alertType' => 'success'
                            ]
                        );
                    } else {
                        return response()->json([
                            'message' => 'Failed updated',
                            'alertType' => 'error'
                        ]);
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        'message' => 'Failed updated',
                        'alertType' => 'error'
                    ]);
                }
            }
        }
        return response()->json([
            'message' => 'Unauthorized',
            'alertType' => 'error'
        ]);
    }

    function getThanaByDistrictId($dist_id)
    {
        $dist = District::with(['thana' => function (Builder $query) {
            $query->where('is_active', true)->select('id', 'name', 'district_id');
        }])->findOrFail($dist_id);
        return $dist->thana;
    }

    function bulk_modal()
    {
        if (can('thana_index')) {
            return view('backend.modules.common_module.thana.modals.bulk');
        }
        return view('error.modals.403');
    }

    function upload_bulk(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => 'required'
        ]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'csv') {
                return response()->json([
                    'message' => 'Please upload a csv file',
                    'alertType' => 'error'
                ]);
            }
            $returnResponse = $this->thanaService->uploadBulk($file);
            return response()->json([
                'message'=>$returnResponse['message'],
                'alertType'=>$returnResponse['status']
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong',
            'alertType' => 'error'
        ]);
    }


}
