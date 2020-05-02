<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryDetails extends Model
{
    protected $table = 'order_delivery_details';

    protected $fillable = ['order_id', 'delivery_reference', 'delivery_address', 'delivery_drop_off_coordinate', 'delivery_contact_phone_number', 'delivery_locality', 'delivery_charge', 'delivery_country'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'id', 'order_id');
    }
}
