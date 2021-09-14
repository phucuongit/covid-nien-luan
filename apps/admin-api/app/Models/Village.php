<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use App\Models\District;

class Village extends Model
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
        'district_id',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
