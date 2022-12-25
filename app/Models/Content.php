<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model {

    protected $table = 'content';

    protected $appends = ['images'];

    public function elements() {
        return $this->hasMany('App\Models\ContentElement')->orderBy('sort_order', 'ASC');
    }

    public function getTemplateTagByName($tagName) {

        if($this->template_tags) return false;

        foreach ($this->template_tags as $tag) {
            if ($tag && strcmp($tag['key'], $tagName) === 0) return $tag;
        }

        return false;
    }

    public function getImagesAttribute() {
        return File::where(['object_id' => $this->id, 'object_type_id' => 'content:image'])->get();
    }

    public function getTemplateAttribute($value) {
        return sprintf('content-templates.%s', explode('.', $value)[0]);
    }

    public function getTemplateTagsAttribute($tags) {

        $tags = unserialize($tags);

        if($tags) {
            foreach($tags as $key => $tag) {
                if (!$tag) continue;
                $tags[$tag['key']] = $tag['value'];
                unset($tags[$key]);
            }
        }

        return $tags;
    }

}
