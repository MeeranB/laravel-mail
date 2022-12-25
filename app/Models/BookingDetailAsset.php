<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetailAsset extends Model {

    protected $table = 'booking_detail_asset';

    public $timestamps = false;

    const ACTIVE_NAMES = ['ean_json_offer', 'hb_json_offer', 'supplier_offer', 'room_data', 'supplier_room_offer'];

    //The following functions may not work if attempting to retrieve from an inactive bookingDetailAsset

    public function getCancellationPolicies() {
        return collect(json_decode($this->value)->cancellationPolicies ?? json_decode($this->value)->rate->cancellationPolicies) ?? null;
    }

    public function getCancellationDate() {
        return json_decode($this->value)->cancellationDate ?? json_decode($this->value)->rate->cancellationDate ?? null;
    }
}
