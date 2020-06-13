<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
       if (!$user['status']) {
           $this->logout();
           return $this->fail(ACCOUNT_HAD_FREEZE);
       }

        $auth = [
            'info' => [
                'name' => $user['name'],
                'email' => $user['email']
            ],
            'meta' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
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
}
