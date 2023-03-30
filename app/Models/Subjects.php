<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subjects extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];

    public function section()
    {
        return $this->belongsTo(Sections::class);
    }
}
