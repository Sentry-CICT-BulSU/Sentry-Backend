<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomKeyLogs extends Model
{
    use HasFactory, SoftDeletes;

    public const RETURNED = '0';
    public const BORROWED = '1';
    public const LOST = '2';
    public const STATUSES = [
        self::RETURNED => 'Returned',
        self::BORROWED => 'Borrowed',
        self::LOST => 'Lost',
    ];

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];

    public function roomKey()
    {
        return $this->belongsTo(RoomKeys::class, 'room_key_id');
    }
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }

    public function getStatusAttribute($value)
    {
        return self::STATUSES[$value];
    }
}
