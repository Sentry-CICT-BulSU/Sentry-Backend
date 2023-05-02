<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedules extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [
        // 'date_start' => 'date',
        // 'date_end' => 'date',
        'time_start' => 'time',
        'time_end' => 'time',
        'active_days' => 'array',
    ];

    public function attendance()
    {
        return $this->hasOne(Attendances::class, 'schedule_id')->latestOfMany();
    }
    public function attendances()
    {
        return $this->hasMany(Attendances::class)->latest();
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
}
