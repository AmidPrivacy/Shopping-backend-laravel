<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public const ROLES = [
        1 => 'Super admin',
        2 => 'Anbar admini',
        3 => 'Mərkəz admini',
        4 => 'Operator'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            if($model->role == 4) {
                $model->referral_code = (string) random_int(100000, 999999);
            }
        });

    }

    public function companies()
    {
        return $this->belongsToMany(Companies::class, 'courier_companies', 'courier_id', 'company_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'courier_orders', 'courier_id', 'order_id');
    }

// public function getRoleAttribute($value) {
//     return ['name' => self::ROLES[$value], 'value' => $value];
// }


}
