<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupSet extends Model {

    protected $table = 'group_set';

    public function groups() {
        return $this->hasMany('App\Models\Group', 'group_set_id');
    }

    public function groupSetGroups() {
        return $this->hasMany('App\Models\GroupSetGroup');
    }
}
