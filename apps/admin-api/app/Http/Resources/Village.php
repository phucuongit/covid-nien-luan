<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Village extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'district_id' => $this->gso_id,
            // 'gso_id' => $this->gso_id,
        ];
    }
}
