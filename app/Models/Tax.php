<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model {

    const PER_PERSON_PER_NIGHT = 0;
    const PER_ROOM_PER_NIGHT = 1;

    protected $table = 'tax';

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function country(){
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function destination(){
        return $this->hasOne('App\Models\Category', 'id', 'destination_id'); 
    }

    public function currency(){
        return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
    }

}