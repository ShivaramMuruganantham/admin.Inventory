<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';

    protected $guarded = [];

    public function user() {
        return $this->hasMany(User::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function permissions() {
        return $this->hasMany(Permission::class);
    }

    public function revenues() {
        return $this->hasMany(Revenue::class);
    }
}
