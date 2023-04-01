<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomKeys extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        '0' => 'Available',
        '1' => 'In Use',
        '2' => 'Lost',
    ];

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];

    public function room()
    {
        return $this->belongsTo(Rooms::class);
    }
    public function schedules()
    {
        return $this->hasManyThrough(Schedules::class, Rooms::class, 'id', 'room_id');
    }
}
