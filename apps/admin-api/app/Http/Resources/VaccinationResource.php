<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserShortResource;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\Vaccine_typeResource;

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
        $data = [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        $data['user'] = 
            new UserShortResource($this->user);
        $data['user_create_by'] = 
            new UserShortResource($this->user_create_by);
        $data['images'] = 
            new ImageCollection($this->images);
        $data['vaccine_type'] =
            $this->vaccine_type()->first(['id','name']);

        return $data;
    }
}
