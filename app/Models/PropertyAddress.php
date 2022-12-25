<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyAddress extends Model {

    protected $table = 'property_address';

    public function property() {
        return $this->belongsTo('App\Models\Property');
    }

    public function country() {
        return $this->belongsTo('App\Models\Country');
    }

    public function createMeta() {

        return [
            'address1' => $this->address_1,
            'address2' => $this->address_2,
            'postcode' => $this->postcode,
            'city' => $this->city,
            'county' => $this->county,
            'province' => !$this->county ? $this->county : $this->city,
            'countryCode' => $this->country ? $this->country->country_id : "",
            'countryName' => $this->country ? $this->country->name : ""
        ];
        
    }
}
