<?php

namespace App\Http\Controllers\Api;


use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends BaseController
{
    public function index(Request $request)
    {
        $list = Device::query()
            ->when($request->filled('sample_num'), function ($query) use ($request) {
                $query->whereHas('samples', function ($sql) use ($request) {
                    $sql->where('sample_num', '=', $request->input('sample_num'));
                });
            })
            ->select('id', 'name', 'device_num')
            ->latest()
            ->get();

        if ($list->isNotEmpty()) {
            return $this->success($list);
        }

        return $this->success([], '没有数据');
    }
}
