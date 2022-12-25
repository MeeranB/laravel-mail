<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListAsset extends Model {

    protected $table = 'list_asset';

    protected $hidden = ['id', 'object_id', 'mask_settings', 'stamp_updated', 'object_type_id', 'datetime_updated'];

    protected $appends = ['display'];

    public function getDisplayAttribute() {
        return ucwords(str_replace('_', ' ', $this->name));
    }

}
