<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $guarded = [];

    public function shops() {
        return $this->belongsTo(Shop::class);
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function permissions() {
        return $this->hasMany(Permission::class);
    }
}
