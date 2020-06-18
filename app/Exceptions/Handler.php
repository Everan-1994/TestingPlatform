<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        InvalidRequestException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // 用户认证的异常
        if ($exception instanceof UnauthorizedHttpException) {
            switch ($exception->getMessage()) {
                case 'Token not provided':
                    throw new InvalidRequestException(config('error_code')[TOKEN_NOT_PROVIDED], Response::HTTP_UNAUTHORIZED, TOKEN_NOT_PROVIDED);
                    break;
                case 'The token has been blacklisted':
                    throw new InvalidRequestException(config('error_code')[TOKEN_BLACKLISTED], Response::HTTP_UNAUTHORIZED, TOKEN_BLACKLISTED);
                    break;
                default:
                    throw new InvalidRequestException($exception->getMessage(), Response::HTTP_UNAUTHORIZED, $exception->getCode());
                    break;
            }
        }

        if ($exception instanceof JWTException) {
            switch ($exception->getMessage()) {
                case 'Token could not be parsed from the request.':
                    throw new InvalidRequestException(config('error_code')[UNRESOLVED_TOKEN], Response::HTTP_UNAUTHORIZED, UNRESOLVED_TOKEN);
                    break;
                case 'Token Signature could not be verified.':
                    throw new InvalidRequestException(config('error_code')[INVALID_TOKEN], Response::HTTP_UNAUTHORIZED, INVALID_TOKEN);
                    break;
                case 'Token has expired':
                    throw new InvalidRequestException(config('error_code')[TOKEN_HAS_EXPIRED], Response::HTTP_UNAUTHORIZED, TOKEN_HAS_EXPIRED);
                    break;
                default:
                    throw new InvalidRequestException($exception->getMessage(), Response::HTTP_UNAUTHORIZED, $exception->getCode());
                    break;
            }
        }

        if ($exception instanceof TokenExpiredException) {
            switch ($exception->getMessage()) {
                case 'Token has expired':
                    throw new InvalidRequestException(config('error_code')[TOKEN_BLACKLISTED], Response::HTTP_UNAUTHORIZED, TOKEN_BLACKLISTED);
                    break;
                default:
                    throw new InvalidRequestException($exception->getMessage(), Response::HTTP_UNAUTHORIZED, $exception->getCode());
                    break;
            }
        }

        if ($exception instanceof NotFoundHttpException) {
            throw new InvalidRequestException(config('error_code')[SYSTEM_METHOD_NOT_EXISI], Response::HTTP_NOT_FOUND, SYSTEM_METHOD_NOT_EXISI);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            throw new InvalidRequestException(config('error_code')[METHOD_NOT_ALLOWED], Response::HTTP_METHOD_NOT_ALLOWED, METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof \ErrorException) {
            throw new InternalException(config('error_code')[SYSTEM_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR, SYSTEM_ERROR);
        }

        if ($exception instanceof \BadMethodCallException) {
            throw new InternalException(config('error_code')[SYSTEM_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR, SYSTEM_ERROR);
        }

        if ($exception instanceof QueryException) {
            throw new InternalException(config('error_code')[SYSTEM_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR, SYSTEM_ERROR);
        }

        if ($exception instanceof ValidationException) {
            throw new InvalidRequestException(config('error_code')[VALIDATION_ERROR], Response::HTTP_UNPROCESSABLE_ENTITY, VALIDATION_ERROR);
        }

        return parent::render($request, $exception);
    }
}
