<?php

namespace Juanyaolin\ApiResponseBuilder;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseStructureContract;

class DefaultApiResponseStructure extends ApiResponseStructureContract
{
    public function make(): array
    {
        $response = [
            'success' => $this->success,
            'code' => $this->apiCode,
            'message' => $this->message,
            'data' => $this->data,
        ];

        if (!$this->success && !is_null($this->data)) {
            $response['debug'] = $this->debugData;
        }

        return $response;
    }
}
