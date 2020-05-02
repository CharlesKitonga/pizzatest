@component('mail::message')
# Successful Order!

Thank you for your order. We are currently processing it. You will have it in less than 30 mins.

**Order ID:** {{ $order->id }}

**Customer Email:** {{ $order->email }}

**Customer Name:** {{ $order->name }}

**Payment Method:** {{ $paymentDetails && $paymentDetails->payment_details_type ? strtoupper($paymentDetails->payment_details_type) : 'N/A' }}

**Delivery Charge:** {{ $deliveryDetails && $deliveryDetails->delivery_charge ? 'Ksh. ' . $deliveryDetails->delivery_charge : '0.00'}}

**Order Total: ** Ksh. {{ $order->total }}

**Items Ordered**

@foreach ($order->orderItems as $item)
Name: {{ $item->product->product_name }} <br>
Price: $. {{ $item->price }} <br>
Quantity: {{ $item->quantity }} <br>
@if($item->productAttribute)
@if($item->productAttribute->accompaniment)
Accompaniment: {{ $item->productAttribute->accompaniment }} {{ $item->productAttribute->price ? '(Ksh. ' . $item->productAttribute->price . ')' : '' }} <br>
@endif
@if($item->productAttribute->size)
Size: {{ $item->productAttribute->size }} <br>
@endif
@endif

@endforeach

You can get further details about your order by logging into our website.

@component('mail::button', ['url' => config('app.url'), 'color' => 'green'])
Go to Website
@endcomponent

Thank you again for choosing us.

Regards,<br>
{{ config('app.name') }}
@endcomponent
