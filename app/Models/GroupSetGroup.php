<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupSetGroup extends Model {

    protected $table = 'group_set_group';

    public function propertyGroups() {
        return $this->hasMany('App\Models\PropertyGroup', 'group_id', 'id');
    }

    public function properties() {
        return $this->hasManyThrough('App\Models\Property', 'App\Models\PropertyGroup', 'group_id', 'id', 'id', 'property_id');
    }
    
    public function propertyClientRelations() {
        return $this->hasMany('App\Models\PropertyClientRelation', 'group_id', 'id');
    }      

}
