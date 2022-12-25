<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyRoomImage extends Model {

    protected $table = 'property_image_room';

    // RE room/file relations! Original DB architecture is storing the IDs in reverse, incorrectly.
    // Do not alter the below keys!
    public function room() {
        return $this->belongsTo('App\Models\PropertyRoom', 'id', 'file_id');
    }

    public function file() {
        return $this->belongsTo('App\Models\File', 'id', 'room_id');
    }

}
