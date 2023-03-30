<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sections extends Model
{
    use HasFactory;

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

    public function getStatusAttribute($status)
    {
        return Str::title($status);
    }
}
