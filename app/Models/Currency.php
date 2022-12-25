<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model {

    protected $table = 'currency';

    public function property() {
        return $this->belongsToMany('App\Models\Property', 'service_currency_country_id');
    }
    
    public function getSymbolOrCode() {
        return empty($this->currency_symbol) ? $this->currency : $this->currency_symbol;
    }

}
