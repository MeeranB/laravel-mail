<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'category';

    protected $hidden = ['level', 'lft', 'rgt', 'path', 'mask_settings', 'meta_title', 'meta_description', 'imported_id', 'location_map_zoom', 'type_id', 'stamp_created', 'stamp_updated', 'datetime_updated'];

    protected $appends = ['category'];

    // public function properties() {
    //     return $this->belongsToMany('App\Models\Property', 'property_category');
    // }

    public function childRelation() {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    public function children() {
        return $this->childRelation()->with(['children', 'properties']);
    }

    public function parent() {
        return $this->belongsToOne('App\Models\Category', 'parent_id');
    }

    public function createTaxonomy() {
        return [
            'name' => $this->name,
            'properties' => $this->properties->pluck('id')
        ];
    }

    public function groupSetGroup() {
        return $this->belongsToMany('App\Models\GroupSetGroup', 'category_client_relation', 'category_id', 'group_id');
    }

    public function clients() {
        return $this->hasManyThrough('App\Models\GroupSetGroup', 'App\Models\CategoryClientRelation', 'category_id', 'id', 'id', 'group_id');
    }

    public function properties() {
        return $this->hasManyThrough('App\Models\Property', 'App\Models\PropertyCategory', 'category_id', 'id', 'id', 'property_id');
    }

    public function getCategoryAttribute() {
        return true;
    }

}
