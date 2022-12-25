<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model {

    const TYPE_SLIDER = 1;
    const TYPE_LISTBOX = 2;
    const TYPE_ROOM = 4;

    protected $table = 'file';

    protected $appends = ['url', 'slider', 'listbox', 'room'];

    protected $hidden = ['id', 'mask_type', 'mask_settings', 'object_id', 'upload_index', 'path', 'tags', 'stamp_updated', 'object_type_id', 'flag_imported', 'remote_id_1', 'datetime_updated'];

    public function getUrlAttribute() {

        if($this->object_type_id == 'property:image') {
            $slider = in_array(self::TYPE_SLIDER, $this->mapTypeToArray($this->mask_type)) ? '/1' : '';
            return sprintf('https://storage.googleapis.com/cr-images/%s/extranet/property/%s%s/%s', env('GCP_IMAGE_BUCKET'), $this->object_id, $slider, $this->name);
        }

        if($this->object_type_id == 'content:image') {
            return sprintf('https://storage.googleapis.com/cr-images/%s/content-list/%s/%s', env('GCP_IMAGE_BUCKET'), $this->object_id, $this->name);
        }

        if($this->object_type_id == 'contentElement:image') {
            return sprintf('https://storage.googleapis.com/cr-images/%s/content-list-element/%s/%s', env('GCP_IMAGE_BUCKET'), $this->object_id, $this->name);
        }

        return NULL;
    }

    public function getSliderAttribute() {
        return in_array(self::TYPE_SLIDER, $this->mapTypeToArray($this->mask_type));
    }

    public function getListboxAttribute() {
        return in_array(self::TYPE_LISTBOX, $this->mapTypeToArray($this->mask_type));
    }

    public function getRoomAttribute() {
        return in_array(self::TYPE_ROOM, $this->mapTypeToArray($this->mask_type));
    }

    private function mapTypeToArray($v) {
        $s = [];
        $t = 1;

        do {
            if (($v & $t) == $t) $s[] = $t;
            $t *= 2;
        } while ($t <= $v);

        return $s;
    }




}
