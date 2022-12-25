<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model {

    const TYPE_BOOKING_SUBMITTER = 'booking:submitter';
    const TYPE_BOOKING_TRAVELLER = 'booking:guest';

    protected $table = 'guest';

    public $timestamps = false;

    public function bookings() {
        return $this->belongsTo('App\Models\Booking');
    }    

    public function country() {
        return $this->belongsTo('App\Models\Country');
    }

}
