<?php

namespace App\Http\Controllers\Backend\CommonModule\Channel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CommonModule\Channel\ChannelRequest;
use App\Models\Backend\CommonModule\Channel;
use App\Services\Backend\DatatableServices\CommonModule\Channel\ChannelDatatableService;
use App\Services\Backend\ModuleServices\CommonModule\Channel\ChannelService;
use App\Traits\FileHandlerTrait;
use Exception;
use function Laravel\Prompts\error;

class ChannelController extends Controller
{
    use FileHandlerTrait;

    protected ChannelService $channelService;
    protected ChannelDatatableService $channelDatatableService;

    public function __construct(ChannelService $channelService, ChannelDatatableService $channelDatatableService)
    {
        $this->channelService = $channelService;
        $this->channelDatatableService = $channelDatatableService;
    }

    function index()
    {
        if(can('channel_index')){
            return view('backend.modules.common_module.channel.index');
        }
        return view('error.403');
    }


    function create(ChannelRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->validated();
        try {
            $this->channelService->createChannel($request);
            $alert = [
                'message' => 'Successfully saved',
                'alertType' => 'success',
            ];
            return redirect()->back()->with($alert);
        } catch (\Throwable $th) {
            $alert = [
                'message' => $th->getMessage(),
                'alertType' => 'error',
            ];
            return redirect()->back()->with($alert);
        }
    }

    /**
     * @throws Exception
     */
    function data(): \Illuminate\Http\JsonResponse
    {
        $data = Channel::all();
        return $this->channelDatatableService->getChannelData($data);

    }

    function update_modal($id)
    {
        if(can('channel_index')){
            try {
                $channel = $this->channelService->details($id);
                if ($channel) {
                    $channel->logo = get_base_path($channel->logo);
                    return view('backend.modules.common_module.channel.modals.edit', compact('channel'));
                }
                return view('error.404');
            } catch (Exception $th) {
                return view('error.404');
            }
        }
        return view('error.modals.403');
    }

    function create_modal(){
        if(can('channel_index')){
            return view('backend.modules.common_module.channel.modals.create');
        }
        return view('error.modals.403');
    }


    function update(ChannelRequest $channelRequest, $id)
    {
        if(can('channel_index')){
            $channelRequest->validated();
            try {
                $this->channelService->updateChannel($channelRequest, $id);
                $alert = [
                    'message' => 'Successfully saved',
                    'alertType' => 'success',
                ];
                return redirect()->back()->with($alert);
            } catch (Exception $th) {
                $alert = [
                    'message' => $th->getMessage(),
                    'alertType' => 'error',
                ];
                return redirect()->back()->with($alert);

            }
        }
        return view('error.403');
    }
}
