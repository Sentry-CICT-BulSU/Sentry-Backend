<?php

namespace App\Models;

use App\Models\Passport\RefreshToken;
use App\Models\Passport\Token;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const FACULTY = '0';
    public const ADMIN = '1';
    public const ATTENDANCE_CHECKER = '2';

    public const TYPES = [
        self::FACULTY => 'Faculty',
        self::ADMIN => 'Admin',
        self::ATTENDANCE_CHECKER => 'Attendance Checker'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_img',
        'first_name',
        'last_name',
        'email',
        'password',
        'type',
        'position',
        'college',
        'contact',
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

    protected $appends = [
        'full_name'
    ];

    public function getFullNameAttribute()
    {
        return Str::title($this->first_name . ' ' . $this->last_name);
    }
    public function getTypeAttribute($type)
    {
        return self::TYPES[$type];
    }
    public function setTypeAttribute($type)
    {

        $this->attributes['type'] = match ($type) {
            self::TYPES[self::ADMIN] => self::ADMIN,
            self::TYPES[self::ATTENDANCE_CHECKER] => self::ATTENDANCE_CHECKER,
            self::TYPES[self::FACULTY] => self::FACULTY,
        };
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
    public function getProfileImgAttribute($profile_img)
    {
        return $profile_img ? asset($profile_img) : null;
    }

    public function passportAccessToken()
    {
        return $this->hasMany(Token::class);
    }
    public function passportRefreshToken()
    {
        return $this->hasMany(RefreshToken::class);
    }
}
