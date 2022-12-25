<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PropertyContactTypeRelation extends Pivot {

    protected $table = 'property_contact_type_relation';

    public function groupSetGroups() {
        return $this->belongsTo('App\Models\GroupSetGroup');
    }

    public function propertyContact() {
        return $this->belongsTo('App\Models\PropertyContact');
    }

}
