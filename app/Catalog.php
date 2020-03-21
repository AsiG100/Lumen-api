<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Catalog extends Model
{
  
    protected $fillable = [
        'id','name', 'popularity', 'products'
    ];

    public function getProductsAttribute($value)
    {
        $items = explode(',', $value);
        return Product::find($items);
    }

}
