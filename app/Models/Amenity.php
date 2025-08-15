<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description', 'venue_id'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function spaces()
    {
        return $this->belongsToMany(Space::class, 'space_amenities');
    }
}
