<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserShortResource;
use App\Http\Resources\ImageCollection;

class VaccinationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['user'] = 
            new UserShortResource($this->user);
        $data['user_create_by'] = 
            new UserShortResource($this->user_create_by);
        $data['images'] = 
            new ImageCollection($this->images);
        return $data;
    }
}
