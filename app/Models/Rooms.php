<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rooms extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];

    public function schedules()
    {
        return $this->hasMany(Schedules::class, 'room_id');
    }
    public function key()
    {
        return $this->hasOne(RoomKeys::class, 'room_id');
    }
}
