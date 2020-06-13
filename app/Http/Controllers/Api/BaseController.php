<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * 成功返回
     * @param int $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], string $message = 'success', $code = 10000)
    {
        return response()->json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * 失败返回
     * @param int $code
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function fail($code = 10000, string $message = null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message ?? config('error_code')[(int) $code]
        ]);
    }
}
