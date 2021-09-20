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
use App\Models\Role;
use App\Models\Vaccination;
use App\Models\Result_test;
use Carbon\Carbon;

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
    //     // 'email_verified_at' => 'datetime',
    //     'create_at' => 'datetime',
    //     'update_at' => 'datetime',
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

    // public function getCreatedAtAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    // }
    
    // public function getUpdatedAtAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    // }

    /**
     * Search users
     */
    public function filterSearch($query, $value)
    {
        return $query
            ->where('identity_card', 'LIKE', '%'.$value.'%')
            ->orWhere('social_insurance', 'LIKE', '%'.$value.'%')
            ->orWhere('fullname', 'LIKE', '%'.$value.'%')
            ->orWhere('address', 'LIKE', '%'.$value.'%')
            ->orWhere('phone', 'LIKE', '%'.$value.'%');
    }

    /**
     * Get all of the user's image.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

     /**
     * Get user's village.
     */
    public function village(){
        return $this->belongsTo(Village::class);
    }

     /**
     * Get user's district.
     */
    public function district()
    {
        return $this->village->district;
        // return $this->hasOneThrough(District::class, Village::class, 'id', 'id', 'village_id', 'district_id');
    }

     /**
     * Get user's province.
     */
    public function province()
    {
        return $this->village->district->province;
    }

     /**
     * Get user's vaccination.
     */
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

     /**
     * Get user's result_test.
     */
    public function result_tests()
    {
        return $this->hasMany(Result_test::class);
    }

     /**
     * Get role's user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}