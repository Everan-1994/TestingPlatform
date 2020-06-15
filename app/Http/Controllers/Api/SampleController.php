<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use App\Models\Sample;
use App\Models\UploadList;
use Illuminate\Http\Request;

class SampleController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (!$request->filled('sample_num')) {
            return $this->fail(VALIDATION_ERROR);
        }

        if (!Sample::query()->where('sample_num', '=', $request->input('sample_num'))->exists()) {
            return $this->fail(VALIDATION_ERROR, '样本编号不存在');
        }

        if (UploadList::query()->where('sample_num', '=', $request->input('sample_num'))->exists()) {
            $type = 2; // 取样下一步
            $upload_list = UploadList::query()->where('sample_num', '=', $request->input('sample_num'))->first();
            $upload_list = [
                'id' => $upload_list->id,
                'get_user' => $upload_list->user->name, // 取样人员
                'created_at' => $upload_list->created_at, // 取样时间
                'send_user' => $upload_list->ss_name, // 送样人员
            ];
        } else {
            $type = 1; // 第一次扫码
            $upload_list = [
                'id' => '',
                'get_user' => '',
                'created_at' => '',
                'send_user'
            ];
        }

        return $this->success(compact('type', 'upload_list'));
    }

    /**
     * 下一步
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nextStep(Request $request)
    {
        if (!$request->filled('sample_num')) {
            return $this->fail(VALIDATION_ERROR);
        }

        if (!$request->filled('ss_name')) {
            return $this->fail(VALIDATION_ERROR);
        }

        $upload_list = UploadList::query()
            ->create([
                'user_id' => auth('api')->user()->id,
                'sample_num' => $request->input('sample_num'),
                'ss_name' => $request->input('ss_name')
            ]);

        return $this->success([
            'id' => $upload_list->id,
            'created_at' => $upload_list->created_at
        ]);
    }

    /**
     * 写报告
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function report(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'upload_list_id' => 'required',
            'project_id' => 'required',
            'device_id' => 'required',
            'content' => 'required',
        ], [
            'upload_list_id.required' => '上传列表id不能为空',
            'project_id.required' => '项目id不能为空',
            'device_id.required' => '设备仪器id不能为空',
            'content.required' => '报告内容不能为空'
        ]);

        if ($validator->fails()) {
            return $this->fail(VALIDATION_ERROR);
        }

        try {
            Report::query()
                ->create([
                    'user_id' => auth('api')->user()->id,
                    'upload_list_id' => $request->input('upload_list_id'),
                    'project_id' => $request->input('project_id'),
                    'device_id' => $request->input('device_id'),
                    'content' => $request->input('content'),
                ]);
        } catch (\Exception $e) {
            return $this->fail(SYSTEM_ERROR);
        }

        return $this->success();
    }
}
