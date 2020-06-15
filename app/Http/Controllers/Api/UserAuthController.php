<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserAuthController extends BaseController
{
    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $params = $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string|between:5,20',
        ]);

        $token = Auth::guard('api')->attempt([
            'email' => $params['username'] . '@qq.com',
            'password' => $params['password'],
        ]);

        if (!$token) {
            return $this->fail(ACCOUNT_OR_PWD_ERROR);
        }

        // 记录登入日志
        // event(new LoginEvent(\Auth::guard('api')->user(), new Agent(), $request->getClientIp()));

        // 获取 Auth 登录的用户
        $user = Auth::guard('api')->user();

        // 用户是否启用
       if (!$user->status) {
           $this->logout();
           return $this->fail(ACCOUNT_HAD_FREEZE);
       }

        $auth = [
            'info' => [
                'avatar' => env('APP_URL') . $user->avatar,
                'name' => $user->name,
                'sex' => $user->sex,
                'employee_id' => $user->employee_id
            ],
            'meta' => [
                'access_token' => 'Bearer ' . $token,
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            ],
        ];

        return $this->success($auth);
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // $expires_at = Carbon::now()->addMinute();

        // Cache::put('user_id', Auth::id(), $expires_at);
        // Cache::put('username', Auth::user()->username, $expires_at);

        $api_guard = Auth::guard('api');
        $api_guard->setToken($api_guard->getToken());

        // 检查旧 Token 是否有效
        if ($api_guard->check()) {
            // 加入黑名单
            $api_guard->invalidate();
        }

        $api_guard->logout();

        return $this->success();
    }

    /**
     * 个人信息更新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInfo(Request $request)
    {
        if (!$request->filled('update_type')) {
            return $this->fail(VALIDATION_ERROR);
        }

        $user = Auth::guard('api')->user();

        switch ($request->input('update_type')) {
            case 'avatar':
                $validator = \Validator::make($request->all(), [
                    'file'    => 'required'
                ], [
                    'file.required'    => '请上传头像'
                ]);

                if ($validator->fails()) {
                    return $this->fail(VALIDATION_ERROR);
                }

                // 构建存储的文件夹规则，如：articles/201810/10/
                // 文件夹切割能让查找效率更高。
                $folderName = "avatars/".date('Ym/d', time());

                // 将图片上传目标存储路径中
                $fileUrl = $request->file('file')->store($folderName, 'admin');
                // 更新个人信息
                $user->avatar = '/' . $fileUrl;

                $res = [
                    'avatar' => env('APP_URL') . '/' . $fileUrl
                ];
                break;
            default:
                $user->{$request->input('update_type')} = $request->input($request->input('update_type'));
                $res = [];
                break;

        }

        try {
            $user->save();
        } catch (\Exception $e) {
            return $this->fail(VALIDATION_ERROR, '参数个数不正确');
        }

        return $this->success($res);
    }
}
