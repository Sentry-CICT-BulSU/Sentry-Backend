<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const TYPES = [
        'faculty' => '0',
        'admin' => '1',
        'attendance_checker' => '2'
    ];

    public const TYPE_CAST = [
        '0' => 'Faculty',
        '1' => 'Admin',
        '2' => 'Attendance Checker'
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

    public function type_cast()
    {
        return Attribute::make(
        get: function () {
            return self::TYPE_CAST[$this->type];
        },
        );
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
    public function getProfileImgAttribute($profile_img)
    {
        return $profile_img ? asset($profile_img) : null;
    }
}
