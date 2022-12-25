<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\File;

class ContentElement extends Model {

    protected $casts = [
        'template_tags' => 'array'
    ];

    protected $table = 'content_element';

    public $appends = ['images'];

    protected $hidden = ['template'];

    public function content() {
        return $this->belongsTo('App\Models\Content');
    }

    public function getImagesAttribute() {
        return File::where(['object_id' => $this->id, 'object_type_id' => 'contentElement:image'])->get();
    }

    public function getTemplateAttribute($value) {
        return sprintf('content-templates.%s', explode('.', $value)[0]);
    }

    public function getTemplateTagsAttribute($tags) {
        $tags = unserialize($tags);

        foreach($tags as $key => $tag) {
            $tags[$tag['key']] = $tag['value'];
            unset($tags[$key]);
        }

        return $tags;
    }

}
