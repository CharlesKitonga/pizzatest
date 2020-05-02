<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products_Attributes extends Model
{
    protected $fillable = [
        'product_id', 'size' , 'price',
    ];

    public function products() {
    	return $this->belongsTo('App\Product');
    }

    public function orderProduct() {
    	return $this->belongsTo('App\OrderProduct',  'id');
    }
}
