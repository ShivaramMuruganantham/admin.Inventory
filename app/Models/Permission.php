<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $guarded = [];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function shop(){
        return $this->belongsTo(Shop::class);
    }
}
