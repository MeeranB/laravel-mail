<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PropertyCategory extends Pivot {

    protected $table = 'property_category';

    public function property() {
        return $this->belongsToOne('App\Models\Property', 'id', 'property_id');
    }

}
