<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sections extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];

    public function semester()
    {
        return $this->belongsTo(Semesters::class);
    }

    public function facultyAdviser()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subjects::class);
    }
    public function schedule()
    {
        return $this->hasOne(Schedules::class);
    }

    public function getStatusAttribute($status)
    {
        return Str::title($status);
    }
}
