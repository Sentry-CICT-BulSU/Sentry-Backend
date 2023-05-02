<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendances extends Model
{
    use HasFactory, SoftDeletes;

    public const PRESENT = '1';
    public const ABSENT = '0';
    public const NOT_VISITED = '2';
    public const STATUSES = [
        self::PRESENT => 'present',
        self::ABSENT => 'absent',
        self::NOT_VISITED => 'not visited',
    ];
    protected $table = 'attendances';

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function schedule()
    {
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }
}
