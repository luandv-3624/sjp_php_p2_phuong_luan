<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'name_en', 'full_name', 'full_name_en'];

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
