<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    /**
     * Get the menu associated with a bill.
     */
    public function menu()
    {
        return $this->belongsTo('App\Menu');
    }
}
