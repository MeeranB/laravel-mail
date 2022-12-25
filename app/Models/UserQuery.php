<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuery extends Model {

    protected $table = 'user_query';

    public function getName(): string
    {
        //Implemented as array so as to use join method, this avoids erroneous spacing that would occur with other print methods of similar complexity
        $a = array_diff(array($this->first_name, $this->last_name), array(''));
        if (!$a) {
            return null;
        }
        return join(' ', $a);
    }

}
