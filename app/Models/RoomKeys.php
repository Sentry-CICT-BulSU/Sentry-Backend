<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomKeys extends Model
{
    use HasFactory, SoftDeletes;

    public const AVAILABLE = '0';
    public const IN_USE = '1';
    public const LOST = '2';
    public const STATUSES = [
        self::AVAILABLE => 'Available',
        self::IN_USE => 'In Use',
        self::LOST => 'Lost',
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
