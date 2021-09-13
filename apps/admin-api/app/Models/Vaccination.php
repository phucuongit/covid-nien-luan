<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use App\Models\User;

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
    /**
    *   Atrr for the trait filters
    *   @var array
    */
    protected $filterable = [
        'id',
        'user_id',
        'create_by',
        'vaccine_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_create_by()
    {
        return $this->belongsTo(User::class, 'create_by', 'id');
    }

}
