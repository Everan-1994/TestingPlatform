<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class Authenticate extends BaseMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     *
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @param null $role
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     */
    public function handle($request, Closure $next, $guard = null, $role = null)
    {
        // 检查此次请求中是否带有 token，如果没有则抛出异常。
        $this->checkForToken($request);

        $auth = $this->auth->guard($guard);

        try {
            // 防止 token 越权-串码, 判断 token 角色。
            if ($auth->getPayload()->get('role') != $role) {
                throw new UnauthorizedHttpException('jwt-auth',
                    config('error_code')[INCORRECT_IDENTIFY_INFORMATION],
                    null,
                    INCORRECT_IDENTIFY_INFORMATION
                );
            }

            return $next($request);
        } catch (TokenBlacklistedException $exception) {
            // token 已加入黑名单
            throw new UnauthorizedHttpException('jwt-auth',
                config('error_code')[TOKEN_BLACKLISTED],
                null,
                TOKEN_BLACKLISTED
            );
        } catch (TokenExpiredException $exception) {
            try {
                // token 信息
                $token_info = $auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray();

                // 防止 token 越权-串码 判断 token 角色。
                if ($token_info['role'] != $role) {
                    throw new UnauthorizedHttpException('jwt-auth',
                        config('error_code')[INCORRECT_IDENTIFY_INFORMATION],
                        null,
                        INCORRECT_IDENTIFY_INFORMATION
                    );
                }

                // 确保本次失效的请求是正常访问的
                $auth->onceUsingId($token_info['sub']);
                // 防止前端 无法获取 Authorization:token
                $response = $next($request);
                $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
                // 刷新 token 在响应头 返回新 token
                return $this->setAuthenticationHeader($response, $auth->refresh());
            } catch (JWTException $exception) {
                throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
            }
        }
    }
}
