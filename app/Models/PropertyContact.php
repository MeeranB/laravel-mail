<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyContact extends Model {

    protected $table = 'property_contact';

    public function property() {
        return $this->belongsTo('App\Models\Property');
    }

    public function typeRelations() {
        return $this->hasMany('App\Models\PropertyContactTypeRelation', 'property_contact_id', 'id');
    }

    public function typeGroups() {
        return $this->belongsToMany('App\Models\GroupSetGroup', 'property_contact_type_relation', 'property_contact_id', 'group_id');
    }
}
