<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use App\Models\User;
use App\Models\Image;
use App\Models\Vaccine_type;
use Carbon\Carbon;
use DateTime;

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
        'time'
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
        'time'
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    //     'deleted_at'
    // ];

    // public function getCreatedAtAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    // }
    
    // public function getUpdatedAtAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    // }

    /**
     * Search vaccination
     */
    public function filterSearch($query, $value)
    {
        $date = date("y-m-d", strtotime($value));
        return $query
            ->where('created_at', 'LIKE', '%'.$date.'%') // Cant use wheredate, cause it will convert to carbon type
            ->orWhereHas('user' , function($query) use ($value) {
                $query->where('fullname', 'LIKE', '%' . $value . '%');
             })
            ->orWhereHas('user_create_by' , function($query) use ($value) {
                $query->where('fullname', 'LIKE', '%' . $value . '%');
             })
            ->orWhereHas('vaccine_type' , function($query) use ($value) {
                $query->where('name', 'LIKE', '%' . $value . '%');
             });
    }

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

    /**
     * Get vaccination's user create.
     */
    public function vaccine_type()
    {
        return $this->belongsTo(Vaccine_type::class);
    }
}
