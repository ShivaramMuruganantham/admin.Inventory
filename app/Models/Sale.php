<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';

    protected $guarded = [];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(Sale_item::class);
    }

    public function revenue() {
        return $this->hasMany(Revenue::class);
    }
}
