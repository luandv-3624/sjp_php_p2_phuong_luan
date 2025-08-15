<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceSpecialHour extends Model
{
    use HasFactory;

    protected $fillable = ['space_id', 'date', 'open_time', 'close_time', 'is_closed', 'reason'];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}
