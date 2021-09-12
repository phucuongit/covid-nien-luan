<?php
namespace App\Models; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\Filterable;
use App\Models\Image;
use App\Models\Village;
use App\Models\District;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Filterable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identity_card',
        'social_insurance',
        'username',
        'password',
        'fullname',
        'birthday',
        'gender',
        'address',
        'phone',
        'village_id',
        'role_id',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    /**
    *   Atrr for the trait filters
    *   @var array
    */
    protected $filterable = [
        'id',
        'identity_card',
        'social_insurance',
        'username',
        'password',
        'fullname',
        'birthday',
        'gender',
        'address',
        'phone',
        'village_id',
        'role_id',
    ];

    /**
     * Get the user's image.
     */
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function village(){
        return $this->belongsTo(Village::class);
    }
}