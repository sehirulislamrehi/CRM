<?php

namespace App\Services\Backend\ModuleServices\CommonModule\Channel;

use App\Models\Backend\CommonModule\Channel;
use App\Traits\FileHandlerTrait;
use Exception;

/**
 * Handel backed business logic for creating and updating channel
 * @package App\Services\Backend\ModuleServices\CommonModule\Channel
 * @author Alimul Mahfuz Tushar <automation33@mis.prangroup.com>
 * @copyright MIS RFL
 */
class ChannelService
{
    use FileHandlerTrait;

    protected ?string $imageLocation;

    public function __construct()
    {
        $this->imageLocation = entity_folder_mapping('channel');
    }

    public function createChannel($requestData): bool
    {
        $channel = new Channel();
        $channel->name = $requestData->input('name');
        $channel->channel_type = $requestData->input('channel_type');
        $channel->channel_number = $requestData->input('channel_number');
        $channel->is_active = $requestData->input('is_active');

        if ($requestData->has('logo')) {
            $logo = $requestData->file('logo');
            $name = time() . '.' . $logo->getClientOriginalExtension();
            if ($this->upload_file($logo, $this->imageLocation, $name)) {
                $channel->logo = $name;
            }
        }
        if ($channel->save()) {
            return true;
        }
        return false;
    }


    /**
     * @throws Exception
     */
    public function updateChannel($requestData, $id)
    {
        $channel = Channel::find($id);

        if (!$channel) {
            throw new Exception('Resource not found');
        }

        $channel->name = $requestData->input('name');
        $channel->channel_number = $requestData->input('channel_number');
        $channel->channel_type = $requestData->input('channel_type');
        $channel->is_active = $requestData->input('is_active');

        if ($requestData->has('logo')) {
            $this->remove_file(entity_folder_mapping('channel') . '/' . $channel->logo);
            $logo = $requestData->file('logo');
            $name = time() . '.' . $logo->getClientOriginalExtension();
            if ($this->upload_file($logo, $this->imageLocation, $name)) {
                $channel->logo = $name;
            } else {
                throw new Exception('Failed to save logo');
            }
        }

        return $channel->save();
    }

    function details($id)
    {
        $channel = Channel::find($id);
        if ($channel) {
            return $channel;
        }
        return false;
    }
}
