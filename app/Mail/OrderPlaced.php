<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Order;
use App\DeliveryDetails;
use App\PaymentDetails;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $deliveryDetails;
    public $paymentDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, DeliveryDetails $deliveryDetails = null, PaymentDetails $paymentDetails = null)
    {
        $this->order = $order;
        $this->deliveryDetails = $deliveryDetails;
        $this->paymentDetails = $paymentDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Get admin emails from ..env file
        $to = explode(',', env('ADMIN_EMAILS'));

        return $this->to($this->order->email, $this->order->name)
                    ->bcc($to)
                    ->subject('Kilimanjaro Food - Order Placed')
                    ->markdown('emails.orders.placed');
    }
}
