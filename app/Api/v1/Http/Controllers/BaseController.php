<?php

namespace App\Api\v1\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as LaravelBaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class BaseController extends LaravelBaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * HTTP header status code.
     *
     * @var int
     */
    protected $statusCode = 200;
    /**
     * Illuminate\Http\Request instance.
     *
     * @var Request
     */
    protected $request;

    /**
     * Getter for statusCode.
     *
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }
    /**
     * Setter for statusCode.
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Response with validation error.
     *
     * @param array $messages
     *
     * @return JsonResponse
     */
    protected function errorValidation(array $messages = [])
    {
        return $this->setStatusCode(422)->respondWithArray(['errors' => $messages]);
    }

    /**
     * Respond with success.
     *
     * @param $message
     * @return JsonResponse
     *
     */
    protected function respondWithSuccess($message = null)
    {
        if (! $message) {
            $message = __('admin_labels.success_label');
        }
        return $this->setStatusCode(200)->respondWithArray(['message' => $message]);
    }

    /**
     * Respond with a given array of items.
     *
     * @param array $array
     * @param array $headers
     *
     * @return JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->statusCode, $headers);
    }
    /**
     * Response with the current error.
     *
     * @param string $message
     *
     * @return mixed
     */
    protected function respondWithError($message)
    {
        return $this->respondWithArray([
            'message'   => $message,
            'error' => [
                'http_code' => $this->statusCode,
            ],
        ]);
    }
    /**
     * Generate a Response with a 403 HTTP header and a given message.
     *
     * @param $message
     *
     * @return JsonResponse
     */
    protected function errorForbidden($message = null)
    {
        if (! $message) {
            $message = __('admin_labels.forbidden');
        }
        return $this->setStatusCode(403)->respondWithError($message);
    }
    /**
     * Generate a Response with a 500 HTTP header and a given message.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function errorInternalError($message = null)
    {
        if (! $message) {
            $message = __('admin_labels.internal_error');
        }
        return $this->setStatusCode(500)->respondWithError($message);
    }
    /**
     * Generate a Response with a 404 HTTP header and a given message.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function errorNotFound($message = null)
    {
        if (! $message) {
            $message = __('admin_labels.not_found');
        }
        return $this->setStatusCode(404)->respondWithError($message);
    }
    /**
     * Generate a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function errorUnauthorized($message = null)
    {
        if (! $message) {
            $message = __('admin_labels.unathorized');
        }
        return $this->setStatusCode(401)->respondWithError($message);
    }
    /**
     * Generate a Response with a 400 HTTP header and a given message.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function errorWrongArgs($message = null)
    {
        if (! $message) {
            $message = __('admin_labels.wrong_args');
        }
        return $this->setStatusCode(400)->respondWithError($message);
    }

    /**
     * Generate a Response with a 418 HTTP header and a given message.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function errorAuth($message = null)
    {
        if (!$message) {
            $message = __('admin_labels.wrong_credentials');
        }
        return $this->setStatusCode(418)->respondWithError($message);
    }

    /**
     * Generate a Response with a 403 HTTP header and a given message.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function errorLocked($message = null)
    {
        if (! $message) {
            $message = __('admin_labels.error_locked');
        }
        return $this->setStatusCode(403)->respondWithError($message);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
