<?php
namespace App\Models; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\Filterable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Filterable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'username',
        'password',
        'identify_card',
        'birthday',
        'gender',
        'avatar',
        'address',
        'phone',
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

    /*
        For the trait filters
    */
    protected $filterable = [
        'fullname',
        'username',
        'password',
        'identify_card',
        'birthday',
        'gender',
        'avatar',
        'address',
        'phone',
        'role_id',
    ];
}