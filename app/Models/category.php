<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $guarded = [];

    public $timestamps = false;

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function sale_items() {
        return $this->hasMany(Sale_item::class);
    }
}
