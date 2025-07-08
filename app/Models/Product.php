<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = [];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function saleItems() {
        return $this->hasMany(Sale_item::class);
    }

    public function inventory() {
        return $this->hasMany(Inventory::class)->select('id', 'shop_id', 'product_id', 'stock_qty');
    }
}
