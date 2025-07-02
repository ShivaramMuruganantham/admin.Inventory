<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop_user extends Model
{
    protected $table = 'shop_users';

    protected $guarded = [];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
