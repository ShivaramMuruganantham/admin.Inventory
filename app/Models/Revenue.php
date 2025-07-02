<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $table = 'revenues';

    public $guarded = [];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function sale() {
        return $this->belongsTo(Sale::class);
    }

    public function saleItem() {
        return $this->belongsTo(Sale_item::class);
    }
}
