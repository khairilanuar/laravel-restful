<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /*
     * Common Rest responses: https://www.toptal.com/laravel/restful-laravel-api-tutorial
     *
     * 200: OK. The standard success code and default option.
     * 201: Object created. Useful for the store actions.
     * 204: No content. When an action was executed successfully, but there is no content to return.
     * 206: Partial content. Useful when you have to return a paginated list of resources.
     * 400: Bad request. The standard option for requests that fail to pass validation.
     * 401: Unauthorized. The user needs to be authenticated.
     * 403: Forbidden. The user is authenticated, but does not have the permissions to perform an action.
     * 404: Not found. This will be returned automatically by Laravel when the resource is not found.
     * 500: Internal server error. Ideally you're not going to be explicitly returning this, but if something unexpected breaks, this is what your user is going to receive.
     * 503: Service unavailable. Pretty self explanatory, but also another code that is not going to be returned explicitly by the application.
     */
    // Deprecated: should use \use Illuminate\Http\Response::HTTP_* instead
    public const API_STATUS_OK = 200;
    public const API_STATUS_CREATED = 201;
    public const API_STATUS_EMPTY = 204;
    public const API_STATUS_PARTIAL = 206;
    public const API_STATUS_BAD = 400;
    public const API_STATUS_UNAUTHENTICATED = 401;
    public const API_STATUS_FORBIDDEN = 403;
    public const API_STATUS_NOT_FOUND = 404;
    public const API_STATUS_SERVER_ERROR = 500;
    public const API_STATUS_SERVICE_UNAVAILABLE = 503;

    /**
     * base response method.
     *
     * @param mixed $data
     *
     * @return JsonResponse
     */
    protected function response($data, string $message, int $status = Response::HTTP_OK, bool $success = true)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'payload' => $data,
        ];

        return response()
            ->json($response, $status)
            ;
    }

    /**
     * return success response.
     *
     * @param mixed $data
     *
     * @return JsonResponse
     */
    protected function sendSuccess($data, string $message, int $status = Response::HTTP_OK)
    {
        return $this->response($data, $message, $status);
    }

    /**
     * return error response.
     *
     * @param mixed $data
     * @param int code
     *
     * @return JsonResponse
     */
    protected function sendError(string $message, $data = [], int $status = Response::HTTP_BAD_REQUEST)
    {
        return $this->response($data, $message, $status, false);
    }
}
