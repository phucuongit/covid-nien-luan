<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use App\Models\User;
use App\Models\Image;

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

    /**
     * Get all of the vaccination's image.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    /**
     * Get vaccination's user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get vaccination's user create.
     */
    public function user_create_by()
    {
        return $this->belongsTo(User::class, 'create_by', 'id');
    }

}
