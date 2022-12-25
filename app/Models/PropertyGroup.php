<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyGroup extends Model {

    protected $table = 'property_group';

    public function property() {
        return $this->belongsToOne('App\Models\Property', 'property_id', 'id');
    }

    public function groupSetGroup() {
        return $this->belongsTo('App\Models\GroupSetGroup', 'group_id', 'id');
    }

}
