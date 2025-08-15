<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id', 'name', 'address', 'ward_id', 'lat', 'lng', 'description', 'status'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function spaces()
    {
        return $this->hasMany(Space::class);
    }

    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'venue_managers');
    }
}
