<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemSettings extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sys_settings';

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];
}
