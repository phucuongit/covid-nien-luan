<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Village as VillageResource;
use App\Http\Resources\District as DistrictResource;
use App\Http\Resources\Province as ProvinceResource;
use App\Http\Resources\VaccinationCollection;
use App\Http\Resources\Result_testCollection;

class UserLook_upResource extends JsonResource
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
            // 'identity_card' => $this->identity_card,
            // 'social_insurance' => $this->social_insurance,
            'fullname' => $this->fullname,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'address' => $this->address,
            'address_full' => 
            [
                "village" => 
                    $this->village()->first('name'),
                "district" => 
                    $this->district()->first('name'),
                "province" => 
                    $this->province()->first('name')
            ],
            // 'phone' => $this->phone,
            'images' => 
                ImageResource::collection($this->images),
            'vaccinations' =>
                new VaccinationCollection($this->vaccinations),
            'result_tests' =>
                new Result_testCollection($this->result_tests)

        ];
    }
}
