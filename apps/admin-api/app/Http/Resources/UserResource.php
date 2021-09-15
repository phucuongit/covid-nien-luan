<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Village as VillageResource;
use App\Http\Resources\District as DistrictResource;
use App\Http\Resources\Province as ProvinceResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\ImageResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'identity_card' => $this->identity_card,
            'social_insurance' => $this->social_insurance,
            'username' => $this->username,
            'fullname' => $this->fullname,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'address' => $this->address,
            'address_full' => 
            [
                "village" => new VillageResource($this->village),
                "district" => new DistrictResource($this->village->district),
                "province" => new ProvinceResource($this->village->district->province),
            ],
            'phone' => $this->phone,
            // 'village_id' => $this->village_id,
            // 'role_id' => $this->role_id,
            'role' => $this->role()->get('name'),
            'images' => ImageResource::collection($this->images)
        ];
    }
}
