<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->pivot->id,
            'game' => $this->name,
            'input' => $this->pivot->input,
            'points' => $this->pivot->points,
            'created_at' => $this->pivot->created_at->format('Y-m-d H:i:s')
        ];
    }
}
