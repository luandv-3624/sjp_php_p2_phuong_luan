<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'space_id', 'start_time', 'end_time', 'check_in', 'check_out',
        'status', 'status_payment', 'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
