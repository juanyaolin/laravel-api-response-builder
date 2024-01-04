<?php

namespace Juanyaolin\ApiResponseBuilder\Structures;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseStructureContract;

class DefaultStructure extends ApiResponseStructureContract
{
    // Notice that the `additional` property is not used by this structure

    public function make(): array
    {
        $response = [
            'success' => $this->success,
            'code' => $this->apiCode,
            'message' => $this->message,
            'data' => $this->data,
        ];

        if (!$this->success && !is_null($this->debugData)) {
            $response['debug'] = $this->debugData;
        }

        return $response;
    }
}
