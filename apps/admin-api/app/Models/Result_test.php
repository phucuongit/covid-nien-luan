<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use App\Models\User;
use Carbon\Carbon;

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
        'id',
        'status',
        'user_id',
        'create_by',
    ];

    // public function getCreatedAtAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    // }
    // public function getUpdatedAtAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    // }

    /**
     * Search result_test
     */
    public function filterSearch($query, $value)
    {
        return $query
            ->where('status', 'LIKE', '%'.$value.'%')
            ->orWhere('created_at', 'LIKE', '%'.$value.'%') // Cant use wheredate, cause it will convert to carbon
            ->orWhereHas('user' , function($query) use ($value) {
                $query->where('fullname', 'LIKE', '%' . $value . '%');
             })
            ->orWhereHas('user_create_by' , function($query) use ($value) {
                $query->where('fullname', 'LIKE', '%' . $value . '%');
             });
    }

    /**
     * Get all of the result_test's image.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Get result_test's user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
     * Get result_test's user create by.
     */
    public function user_create_by()
    {
        return $this->belongsTo(User::class, 'create_by', 'id');
    }
}
