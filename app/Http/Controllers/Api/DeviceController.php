<?php

namespace App\Http\Controllers\Api;


use App\Models\Device;

class DeviceController extends BaseController
{
    public function index()
    {
        $list = Device::query()->select('id', 'name')->latest()->get();

        if ($list->isNotEmpty()) {
            return $this->success($list);
        }

        return $this->success([], '没有数据');
    }
}
