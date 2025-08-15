<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
       'venue_id', 'name', 'space_type_id', 'capacity', 'price_type_id', 'price', 'description', 'status'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function type()
    {
        return $this->belongsTo(SpaceType::class, 'space_type_id');
    }

    public function priceType()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'space_amenities');
    }

    public function operatingHours()
    {
        return $this->hasMany(SpaceOperatingHour::class);
    }

    public function specialHours()
    {
        return $this->hasMany(SpaceSpecialHour::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
