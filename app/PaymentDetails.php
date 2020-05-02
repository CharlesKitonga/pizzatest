<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    protected $table = 'order_payment_details';

    protected $fillable = ['order_id', 'payment_details_type', 'payment_details_reference', 'payment_details_amount', 'payment_details_status', 'payment_details_phone_number', 'payment_details_created_at', 'payment_details_updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'id', 'order_id');
    }
}
