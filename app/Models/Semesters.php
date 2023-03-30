<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Nette\Utils\Json;

class Semesters extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [
        // 'duration' => 'array'
    ];

    public function getStatusAttribute($status)
    {
        return Str::title($status);
    }
    public function getDurationAttribute($duration)
    {
        $result = json_decode($duration, true);
        return [
            'start' => Carbon::parse($result['start']),
            'end' => Carbon::parse($result['end']),
        ];
    }
    public function setDurationAttribute($duration)
    {
        $this->attributes['duration'] = Json::encode([
            'start' => Carbon::parse($duration['start'])->toDateTimeString(),
            'end' => Carbon::parse($duration['end'])->toDateTimeString(),
        ]);
    }
}
