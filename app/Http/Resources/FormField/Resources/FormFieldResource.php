<?php

namespace App\Http\Resources\FormField\Resources;

use App\Http\Resources\FormField\Model\FormField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var $this FormField */
        return [
            'type' => $this->getFieldType(),
            'name' => $this->getFieldName(),
            'value' => $this->getFieldValue()
        ];
    }
}
