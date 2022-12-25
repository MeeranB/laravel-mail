<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\BookingDetailAsset;

class BookingDetail extends Model {

    protected $table = 'booking_detail';

    public $timestamps = false;

    const TYPE_OTHER        = 'OTHER';
    const TYPE_APARTMENT    = 'APARTMENT';
    const TYPE_COTTAGE      = 'COTTAGE';
    const TYPE_ROOM         = 'ROOM';
    const TYPE_STUDIO       = 'STUDIO';
    const TYPE_SUITE        = 'SUITE';
    const TYPE_VILLA        = 'VILLA';
    const TYPE_SERVICE      = 'SERVICE';
    const TYPE_TAX          = 'TAX';

    public function assets() {
        return $this->hasMany('App\Models\BookingDetailAsset', 'object_id', 'id');
    }

    public function rates() {
        return $this->hasMany('App\Models\BookingDetailRate', 'booking_detail_id', 'id');
    }

    public function bookings() {
        return $this->belongsTo('App\Models\Booking');
    }

    public function propertyRoom() {
        return $this->belongsTo('App\Models\PropertyRoom');
    }

    public function getLastActiveAsset() {
        return $this->assets->whereIn('name', BookingDetailAsset::ACTIVE_NAMES)->sortByDesc('id', SORT_NUMERIC)->first();
    }

    public function getAssetByName($name) {
        return $this->assets->where('name', $name);
    }

    public function getCancellationPolicies() {
        //Gets cancellation policies for the current room.
        //Pick first in accepted set of assetNames
        $asset = $this->getLastActiveAsset() ?? null;
        if (!$asset) return null;
        $cancellationPolicies = $asset->getCancellationPolicies() ?? null;
        if (!$cancellationPolicies) return null;
        return $cancellationPolicies;
    }
}
