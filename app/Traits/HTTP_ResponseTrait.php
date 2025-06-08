<?php

namespace App\Traits;

trait HTTP_ResponseTrait
{

    public function successAuth($status = true, $message = null, $data = null, $token = null, $code = 200)
    {

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'token' => $token,
        ], $code);
    }

    public function successResponse($status = true, $message = null, $data = null, $code = 200)
    {

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse($status = true, $message = null, $error = null, $code = 400)
    {

        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    public function returnData($data = null, $code = 200)
    {

        return response()->json(
            $data,
            $code
        );
    }

    public function returnMessage($status = true, $message = null, $code = null)
    {

        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    public function paginateResponse($paginator, $message = "Done successfully", $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_more' => $paginator->hasMorePages(),
                'has_prev' => $paginator->currentPage() > 1,
            ],
            'data' => $paginator->items(),
        ], $code);
    }
}
