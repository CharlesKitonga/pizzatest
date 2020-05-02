<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'name', 'address', 'mobile', 'email', 'total', 'dispatch_status', 'status'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany('App\OrderProduct','order_id');
    }

    public function orderItems()
    {
        return $this->hasMany('App\OrderProduct');
    }
    //this is used in the admin end on orders
    public function Items(){
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    public function orderUsers()
    {
        return $this->belongsTo('App\User');
    }

    public function deliveryDetails()
    {
        return $this->hasMany('App\DeliveryDetails', 'order_id', 'id');
    }

    public function paymentDetails()
    {
        return $this->hasMany('App\PaymentDetails', 'order_id', 'id');
    }

}
