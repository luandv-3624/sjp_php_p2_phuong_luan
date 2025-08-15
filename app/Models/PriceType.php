<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'name_en'];

    public function spaces()
    {
        return $this->hasMany(Space::class);
    }
}
