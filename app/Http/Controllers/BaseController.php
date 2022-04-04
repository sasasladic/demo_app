<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{

    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @param array $additional
     * @return JsonResponse
     */
    public function returnResponseSuccess($data, string $message, array $additional = [], int $code = 200): JsonResponse
    {
        $responseData = $this->formatResponseData($data, true, $message, $additional);

        return response()->json($responseData, $code);
    }

    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function returnResponseError($data, string $message, int $code = 404): JsonResponse
    {
        $responseData = $this->formatResponseData($data, false, $message);

        return response()->json($responseData, $code);
    }

    /**
     * @param $data
     * @param bool $success
     * @param string $message
     * @param array $additional
     * @return array
     */
    private function formatResponseData($data, bool $success, string $message, array $additional = []): array
    {
        $return = [
            'data' => $data,
            'success' => $success,
            'message' => $message
        ];
        if ($additional) {
            return array_merge($additional, $return);
        }

        return $return;
    }

    protected function returnResponseSuccessWithPagination($data, $message, $additional = [])
    {
        $responseData = [
            'message' => $message,
            'success' => true
        ];

        $additional = array_merge($additional, $responseData);

        return $data->additional($additional);
    }
}
