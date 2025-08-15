<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceOperatingHour extends Model
{
    use HasFactory;

    protected $fillable = ['space_id', 'day_of_week', 'open_time', 'close_time', 'is_closed'];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}
