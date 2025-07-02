<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale_item extends Model
{
    protected $table = 'sale_items';

    protected $guarded = [];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function sale() {
        return $this->belongsTo(Sale::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function revenue() {
        return $this->hasMany(Revenue::class);
    }
}
 