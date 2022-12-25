<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyRoom extends Model {

    protected $table = 'property_room';

    protected $hidden = ['mask_settings', 'room_type_group_id', 'stamp_created', 'stamp_updated', 'property_id', 'smoking_policy_group_id', 'flag_export_to_cmg', 'cmg_export_attempts', 'stamp_exported_to_cmg', 'cmg_export_status', 'cmg_room_uid', 'chicretreats_room_id', 'flag_cmg_issues', 'datetime_updated'];

    public function property() {
        return $this->belongsToOne('App\Models\Property', 'id', 'property_id');
    }

    public function images() {
        return $this->hasManyThrough('App\Models\File', 'App\Models\PropertyRoomImage', 'file_id', 'id', 'id', 'room_id');
    }

    public function amenities() {
        return $this->hasManyThrough('App\Models\Group', 'App\Models\PropertyRoomGroup', 'property_room_id', 'id', 'id', 'group_id');
    }

    public function textAssets() {
        return TextAsset::where(['object_id' => $this->id, 'object_type_id' => 'property-room', 'name' => 'description_short'])->get();
    }

}
