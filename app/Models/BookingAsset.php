<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAsset extends Model {

    protected $table = 'booking_asset';

    public $timestamps = false;

    public function booking() {
        return $this->belongsTo('App\Models\Booking', 'booking_id');
    }

}
