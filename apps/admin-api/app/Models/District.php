<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use App\models\Province;
use App\models\Village;

class District extends Model
{
    use HasFactory, Filterable;
    /**
    *   Atrr for the trait filters
    *   @var array
    */
    protected $filterable = [
        'id',
        'name',
        'gso_id',
        'province_id',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    
    public function village()
    {
        return $this->hasMany(Village::class);
    }
}
