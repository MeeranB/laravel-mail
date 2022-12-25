<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model {

    protected $table = 'source';
    protected $connection = 'mysql';

    const CREATED_AT = 'stamp_created';
    const UPDATED_AT = 'datetime_updated';

    public function property() {
        return $this->belongsTo('App\Models\Property');
    }
}
