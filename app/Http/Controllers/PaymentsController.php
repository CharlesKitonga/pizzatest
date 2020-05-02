<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeliveryDetails;
use App\PaymentDetails;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use App\Order;
use App\SMS\SMS;

class PaymentsController extends Controller
{
    public function viewPayments() {
        return view('frontpages/payments');
    }

    public function processPayments(Request $request) {
        // Get order details
        if(session('orderDetails')['orderId']) {
            $order = Order::where('id', session('orderDetails')['orderId'])->first();
            
            // Getting totals
            $total = 0;

            foreach(session('cart') as $key => $item) {
                $total += $item['price'] * $item['quantity'];
            }
            
            // 1. Store delivery details
            if(session('orderDetails')['orderId']) {
                if(session('delivery_details')) {
                    $total = session('delivery_details') ? ((int) session('delivery_details')['deliveryPrice'] + (int) $total) : $total;
                    // POST delivery details
                    $url = env('DELIVERY_URL', 'https://privateapi.weride.co.ke') . '/delivery';

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); // Setting custom header
                    
                    $curl_post_data = [
                        //Fill in the request parameters with valid values
                        'user_id' => 48,
                        'pickup_cord' => '-1.2558335999999999,36.878745599999995',
                        'dropoff_cord1' => session('delivery_details')['dropoff_cord1'] ? session('delivery_details')['dropoff_cord1'] : null,
                        'destination1' => session('delivery_details')['address'] ? session('delivery_details')['address'] : null,
                        'dropoff_vicinity1' => session('delivery_details')['locality'] ? session('delivery_details')['locality'] : null,
                        'dropOffExtraLocationInfo1' => session('delivery_details')['dropoff_point_description'] ? session('delivery_details')['dropoff_point_description'] : null,
                        'dropOffContactNumber1' => session('delivery_details')['drop_off_point_contact_phone_number'] ? session('delivery_details')['drop_off_point_contact_phone_number'] : null,
                        'dropoff_points_count' => 1,
                        'radio-vehicle' => 'motorcycle',
                        'price' => session('delivery_details')['deliveryPrice'] ? session('delivery_details')['deliveryPrice'] : null,
                        'serviceFee' => session('delivery_details')['serviceFee'] ? session('delivery_details')['serviceFee'] : null,
                        'driverFee' => session('delivery_details')['driverFee'] ? session('delivery_details')['driverFee'] : null,
                        'origin' => 'Kilimanjaro Jamia',
                        'duration_value' => session('delivery_details')['duration'] ? session('delivery_details')['duration'] : null,
                        'distance_value' => session('delivery_details')['distance'] ? session('delivery_details')['distance'] : null,
                        'pickup_vicinity' => 'Nairobi',
                        'polyline' => session('delivery_details')['polyline'] ? session('delivery_details')['polyline'] : null,
                        'scheduledTimeCheckbox' => 'off',
                        'delivery_status' => 'assigned'
                    ];
                    
                    $data_string = json_encode($curl_post_data);

                    // dd($curl_post_data);
                    
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    
                    $curl_response = curl_exec($curl);
                    
                    $deliveryPostData = json_decode($curl_response);

                    // dd($deliveryPostData);

                    if($deliveryPostData && $deliveryPostData->status && $deliveryPostData->status === "success") {
                        $deliveryDetails = [
                            'order_id' => $order->id,
                            'delivery_reference' => $deliveryPostData->data->delivery_order_id ? $deliveryPostData->data->delivery_order_id : null,
                            'delivery_address' => session('delivery_details')['address'] ? session('delivery_details')['address'] : null,
                            'delivery_drop_off_coordinate' => session('delivery_details')['dropoff_cord1'] ? session('delivery_details')['dropoff_cord1'] : null,
                            'delivery_contact_phone_number' => session('delivery_details')['drop_off_point_contact_phone_number'] ? session('delivery_details')['drop_off_point_contact_phone_number'] : null,
                            'delivery_locality' => session('delivery_details')['locality'] ? session('delivery_details')['locality'] : null,
                            'delivery_charge' => session('delivery_details')['deliveryPrice'] ? (int) session('delivery_details')['deliveryPrice'] : null,
                            'delivery_country' => session('delivery_details')['country'] ? session('delivery_details')['country'] : null
                        ];
    
                        $storeDeliveryDetails = $this->storeDeliveryDetails($deliveryDetails);
                    } else {
                        $deliveryDetails = [
                            'order_id' => $order->id ?? null,
                            'delivery_reference' => null,
                            'delivery_address' => session('delivery_details')['address'] ? session('delivery_details')['address'] : null,
                            'delivery_drop_off_coordinate' => session('delivery_details')['dropoff_cord1'] ? session('delivery_details')['dropoff_cord1'] : null,
                            'delivery_contact_phone_number' => session('delivery_details')['drop_off_point_contact_phone_number'] ? session('delivery_details')['drop_off_point_contact_phone_number'] : null,
                            'delivery_locality' => session('delivery_details')['locality'] ? session('delivery_details')['locality'] : null,
                            'delivery_charge' => session('delivery_details')['deliveryPrice'] ? (int) session('delivery_details')['deliveryPrice'] : null,
                            'delivery_country' => session('delivery_details')['country'] ? session('delivery_details')['country'] : null
                        ];
    
                        $storeDeliveryDetails = $this->storeDeliveryDetails($deliveryDetails);
                    }
                }
            }

            // 2. Store payment details
            if($request->payment_type && strtolower($request->payment_type) == 'cash') {
                $paymentDetails = [
                    'order_id' => $order->id,
                    'payment_details_type' => $request->payment_type,
                    'payment_details_reference' => null,
                    'payment_details_amount' => $total,
                    'payment_details_phone_number' => null,
                    'payment_details_status' => 'PAIDINFULL'
                ];

                $storePaymentDetails = $this->storePaymentDetails($paymentDetails);
            }
            
            // Send email & forget cart only after payment confirmation
            $order ? Mail::send(new OrderPlaced($order, $storeDeliveryDetails ?? null, $storePaymentDetails ?? null)) : null;

            // TODO: Send app notification or SMS
            // $sms = new SMS($order);
            // $sendSMS = $order ? $sms->send('+254703826457', 'Order successfully placed.') : null;
            // dd($sendSMS);

            $request->session()->forget('cart');
            $request->session()->forget('orderDetails');
            $request->session()->forget('delivery_details');
        }
        
        return view('frontpages/confirmation');
    }

    /**
     * Store payment details
     */
    public function storePaymentDetails($paymentDetails = null) {
        
        if($paymentDetails) {
            $paymentDetailsInsert = PaymentDetails::create($paymentDetails);
            return $paymentDetailsInsert;
        }

        return null;
    }

    /**
     * Store delivery details
     */
    public function storeDeliveryDetails($deliveryDetails = null) {
        
        if($deliveryDetails) {
            $deliveryDetailsInsert = DeliveryDetails::create($deliveryDetails);
            return $deliveryDetailsInsert;
        }

        return null;
    }

    /**
     * Initiate payments process
     */
    public function initiate(Request $request) {
        // Get data
        $paymentType = $request->payment_type;
        $phoneNumber = $request->phoneNumber;

        // Check if all the data has been passed
        $errorArray = [];

        if(!$paymentType): $errorArray[] = ['name' => 'payment_type', 'text'=>'The payment type is missing.']; endif;
        if(!$phoneNumber): $errorArray[] = ['name' => 'phoneNumber', 'text'=>'The phoneNumber is missing.']; endif;

        if(count($errorArray) != 0):
            return response(['status' => 'error', 'message' => 'There are some missing parameters in your request.', 'list' => $errorArray], 400)
                        ->header('Content-Type', 'application/json');
        endif;
        
        // Proceed processing
		$url = env('PAYMENTS_URL', 'https://paymentsapi.kilimanjarofood.co.ke') . '/v1/pay/initiate';
            
        // Getting totals
        $total = 0;

        if(session('cart')) {
            foreach(session('cart') as $key => $item) {
                $total += $item['price'] * $item['quantity'];
            }
            
            // 1. Store delivery details
            if(session('orderDetails')['orderId']) {
                if(session('delivery_details')) {
                    $total = session('delivery_details') ? ((int) session('delivery_details')['deliveryPrice'] + (int) $total) : $total;
                }

                if(session('user_details')) {

                    $post = [
                        'order_id' => session('orderDetails')['orderId'],
                        'amount' => $total,
                        'phone_number' => $phoneNumber ?? '',
                        'email' => session('user_details')['email'] ? session('user_details')['email'] : '',
                        'payment_type' => $paymentType ? $paymentType : '',
                        'callback_url' => env('APP_URL', 'https://kilimanjarofood.co.ke') . '/api/payments/callback'
                    ];
            
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                    $result = curl_exec($ch);
                    curl_close($ch);
            
                    $result = json_decode($result);
            
                    // Based on the response, update session data accordingly / clear it
                    if($result->status == 'success'):
            
                        if($paymentType == 'mpesa'):
                            $paymentDetails = [
                                'order_id' => session('orderDetails')['orderId'],
                                'payment_details_type' => strtolower($paymentType),
                                'payment_details_reference' => null,
                                'payment_details_amount' => $total,
                                'payment_details_phone_number' => session('user_details')['mobile'] ? '0' . session('user_details')['mobile'] : null
                            ];
            
                            $storePaymentDetails = $this->storePaymentDetails($paymentDetails);

                            // Let the user pay for the order
                            $json = ['status' => 'success', 'data' => $result->data, 'payment_type' => $paymentType, 'amount' => $total, 'result' => $result, 'url' => $url, 'callback_url' => $post['callback_url']];
                            $statusCode = 200;
            
                        else:
                            // Set order as successful
                            $json = ['status' => 'success', 'data' => $result->data, 'payment_type' => $paymentType, 'amount' => $total, 'result' => $result, 'url' => $url, 'callback_url' => $post['callback_url']];	
                            $statusCode = 200;
            
                        endif;
            
                    else:
                        // Return error
                        $json = ['status' => 'error', 'message' => 'Payment failed. Please try again', 'payment_type' => $paymentType, 'amount' => $total, 'result' => $result, 'url' => $url, 'callback_url' => $post['callback_url']];
                        $statusCode = 200;
            
                    endif;

                } else {
                    $json = ['status' => 'error', 'message' => 'There was an error retrieving order data. Kindly reload the page or contact support if the issue persists.'];
                    $statusCode = 400;
                }
            } else {
                $json = ['status' => 'error', 'message' => 'There was an error retrieving order data. Kindly reload the page or contact support if the issue persists.'];
                $statusCode = 400;
            }
        } else {
            $json = ['status' => 'error', 'message' => 'There was an error retrieving cart data. Kindly reload the page or contact support if the issue persists.'];
            $statusCode = 400;
        }

        return response($json, $statusCode)
                    ->header('Content-Type', 'application/json');
    }

    public function callback(Request $request)
    {
        // Get parameters
        $billReference = $request->bill_reference;
        $status = $request->status;
        $amount = $request->transaction_amount;
        $orderId = $request->order_id;
        $transactionId = $request->transaction_id;
        $channel = $request->channel;

        // Check if all the data has been passed
        $errorArray = [];

        if(!$orderId): $errorArray[] = ['name' => 'order_id', 'text'=>'The order_id is missing.']; endif;
        if(!$transactionId): $errorArray[] = ['name' => 'transaction_id', 'text'=>'The transaction_id is missing.']; endif;
        if(!$amount): $errorArray[] = ['name' => 'transaction_amount', 'text'=>'The transaction_amount is missing.']; endif;
        if(!$status): $errorArray[] = ['name' => 'status', 'text'=>'The status is missing.']; endif;
        if(!$channel): $errorArray[] = ['name' => 'channel', 'text'=>'The channel is missing.']; endif;

        if(count($errorArray) != 0):
            return response(['status' => 'error', 'message' => 'There are some missing parameters in your request.', 'list' => $errorArray],400)
                        ->header('Content-Type', 'application/json');
        endif;

        // Update payment details
        $updatePaymentDetails = PaymentDetails::where('order_id', $orderId)
                        ->update(['payment_details_type' => strtolower($channel), 'payment_details_reference' => $billReference, 'payment_details_processor_reference' => $transactionId, 'payment_details_status' => $status]);

        // If successfully updated
        if($updatePaymentDetails) {
            $json = ['status' => 'success', 'data' => $request->all()];
            $statusCode = 200;

        } else {
            $json = ['status' => 'error', 'message' => 'There was an error processing the callback.', 'data' => $request->all(), 'updatePaymentDetails' => $updatePaymentDetails];
            $statusCode = 400;
        }

        return response($json, $statusCode)
                    ->header('Content-Type', 'application/json');
    }

    public function search()
    {
        if(session('orderDetails')['orderId']) {
            // Get details
            $paymentDetails = PaymentDetails::where('order_id', session('orderDetails')['orderId'])->first();

            if(strtolower($paymentDetails->payment_details_status) === 'paidinfull' || strtolower($paymentDetails->payment_details_status == 'more')) {

                // Check payment status
                $json = ['status' => 'success', 'data' => null];
                $statusCode = 200;

            } else {
                $json = ['status' => 'error', 'data' => ['payment_status' => strtolower($paymentDetails->payment_details_status) ? strtolower($paymentDetails->payment_details_status) : 'null'], 'message' => 'The payment has not been completed.'];
                $statusCode = 400;
            }

        } else {
            $json = ['status' => 'error', 'data' => null, 'message' => 'There was no order Id found. Your order might have expired. Kindly retry.'];
            $statusCode = 400;
        }

        return response($json, $statusCode)
                    ->header('Content-Type', 'application/json');
    }
}
