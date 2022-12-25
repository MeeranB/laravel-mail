<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryClientRelation extends Model {

    protected $table = 'category_client_relation';

    public function property() {
        return $this->belongsTo('App\Models\Property');
    }

    public function groupSetGroup() {
        return $this->belongsTo('App\Models\GroupSetGroup', 'group_id', 'id');
    }

}
