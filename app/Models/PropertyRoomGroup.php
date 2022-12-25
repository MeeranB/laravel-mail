<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyRoomGroup extends Model {

    protected $table = 'property_room_groups';

    protected $hidden = ['id', 'group_set_id', 'mask_settings', 'stamp_created', 'stamp_updated', 'external_id_1', 'icon_1', 'custom_data', 'external_id_2', 'datetime_updated'];

}
