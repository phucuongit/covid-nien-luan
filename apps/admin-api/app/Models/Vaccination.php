<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class Vaccination extends Model
{
    use HasFactory, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'create_by',
        'vaccine_type_id',
    ];
    /*
    For the trait filters
    */
    protected $filterable = [
        'user_id',
        'create_by',
        'vaccine_type_id',
    ];
}
