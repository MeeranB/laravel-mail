<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

    protected $table = 'group_set_group';

    public function groupSet() {
        return $this->belongsTo('App\Models\GroupSet', 'group_set_id');
    }


}
