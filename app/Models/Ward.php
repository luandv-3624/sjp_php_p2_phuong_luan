<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'province_id', 'name', 'name_en', 'full_name', 'full_name_en'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }
}
