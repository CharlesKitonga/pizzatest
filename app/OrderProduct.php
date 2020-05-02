<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_product';

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'accompaniment_id'];

    public function orderItems()
    {
        return $this->belongsToMany(Product::class)->withPivot('product_name');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function orders()
    {
        return $this->belongsTo('App\User');
    } 

    public function productAttribute()
    {
        return $this->hasOne('App\Products_Attributes', 'id', 'accompaniment_id');
    }
}
