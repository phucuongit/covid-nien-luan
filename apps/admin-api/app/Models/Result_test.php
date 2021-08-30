<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class Result_test extends Model
{
    use HasFactory, Filterable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'user_id',
        'create_by',
    ];
    /**
     *   Atrr for the trait filters
     *   @var array
     */
    protected $filterable = [
        'status',
        'user_id',
        'create_by',
    ];
}
