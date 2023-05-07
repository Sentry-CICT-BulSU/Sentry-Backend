<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedules extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
        'active_days' => 'array',
    ];

    public function attendance()
    {
        return $this->hasOne(Attendances::class, 'schedule_id')->latestOfMany();
    }
    public function attendances()
    {
        $todayFilter = [
            Carbon::now()->startOfDay()->toDateTimeString(),
            Carbon::now()->endOfDay()->toDateTimeString()
        ];
        return $this->hasOne(Attendances::class, 'schedule_id')->whereBetween('created_at', $todayFilter);
    }
    public function section()
    {
        return $this->belongsTo(Sections::class);
    }
    public function room()
    {
        return $this->belongsTo(Rooms::class);
    }
    public function adviser()
    {
        return $this->belongsTo(User::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }
    public function semester()
    {
        return $this->belongsTo(Semesters::class);
    }

    public function getTimeStartAttribute()
    {
        return Carbon::parse($this->attributes['time_start'])->format('h:i A');
    }
    public function getTimeEndAttribute()
    {
        return Carbon::parse($this->attributes['time_end'])->format('h:i A');
    }
}
