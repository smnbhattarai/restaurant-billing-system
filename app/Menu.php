<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * Get Bills for one menu
     */
    public function bills() {
        return $this->hasMany('App\Bill');
    }
}
