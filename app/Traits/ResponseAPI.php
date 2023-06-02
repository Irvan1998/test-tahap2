<?php

namespace App\Traits;

trait ResponseAPI
{
    /**
     * Core of response
     *
     * @param string $message
     * @param array|object $data
     * @param integer $statusCode
     * @param boolean $isSuccess
     */
    public function coreResponse($message, $data = null, $statusCode, $isSuccess = true, $total_data = 0)
    {
        // Send the response
        if ($isSuccess) {
            return response()->json([
                'code' => $statusCode,
                'message' => $total_data > 0 ? $message : 'not found',
                'total_data' => $total_data,
                'data' => $total_data > 0 ? $data : ''
            ], $statusCode);
        } else {
            return response()->json([
                'code' => $statusCode,
                'message' => $message,
                'total_data' => $total_data,
                'data' => ""
            ], $statusCode);
        }
    }

    /**
     * Send any success response
     *
     * @param string $message
     * @param array|object $data
     * @param integer $statusCode
     */
    public function success($message = "ok", $data, $statusCode = 200, $total_data = 0)
    {
        return $this->coreResponse($message, $data, $statusCode, true, $total_data);
    }

    /**
     * Send any error response
     *
     * @param string $message
     * @param integer $statusCode
     */
    public function error($message = "error", $statusCode = 500)
    {
        return $this->coreResponse($message, null, $statusCode, false);
    }
}
