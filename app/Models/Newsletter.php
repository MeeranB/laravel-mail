<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model {

    protected $table = 'subscribe_newsletter';

    public $timestamps = false;

    protected $fillable = ['name', 'email', 'stamp_created'];

}
