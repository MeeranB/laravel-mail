<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingNote extends Model {

    const TYPE_SUBMITTER        = 1;
    const TYPE_ADMINISTRATOR    = 2;

    protected $table = 'booking_note';

    public $timestamps = false;

    public function bookings() {
        return $this->belongsTo('App\Models\Booking');
    }

}