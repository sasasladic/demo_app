<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use stdClass;

trait ConsumesExternalServices
{

    public function makeRequest(string $method, string $requestUrl, array $params = []): stdClass
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->$method($requestUrl, $params);

            $parsedResponse = (object) json_decode($response->body());
            if (!$parsedResponse) {
                return $this->makeError();
            }
            $parsedResponse->success = $response->successful();

            return $parsedResponse;
        }catch (\Exception $exception) {
            return $this->makeError($exception->getMessage());
        }
    }

    private function makeError($message = null): stdClass
    {
        $object = new stdClass();
        $object->success = false;
        $object->message = $message ?? 'Server Error';

        return $object;
    }

}
