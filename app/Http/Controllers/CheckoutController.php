<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use App\Order;
use App\OrderProduct;
use App\Cart;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    public function guestCheckout(Request $request, $id=null){
        $userDetails = User::first();
        //echo "<pre>";print_r($userDetails);die;

        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>";print_r($data);die;

            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->address = $data['address'];
            $user->mobile = $data['mobile'];
            $user->save();

            return redirect()->back()->with('flash_message_success','Your Account Details have been Updated Succesfully');
		}

        $cart = session()->get('cart');

        return view('frontpages/checkout')->with(compact('userDetails', 'cart'));
	}

    public function Checkout(Request $request, $session_id = null) {


        $cart = session()->get('cart');

        return view('frontpages/checkout');
	}

	//create a charge but for now its cash on delivery
    public function storeOrder(Request $request) {
		$data = $request->all();

		$order = $this->addToOrdersTables($request, null);

		// Store delivery details in session data
		$delivery_details = [
			'deliveryPrice' => $request->deliveryPrice,
			'country' => $request->country,
			'address' => $request->address,
			'dropoff_point_description' => $request->dropoff_point_description,
			'dropoff_cord1' => $request->dropoff_cord1,
			'address' => $request->address,
			'locality' => $request->locality,
			'distance' => $request->distance,
			'duration' => $request->duration,
			'delivery_description' => $request->delivery_description,
			'drop_off_point_contact_phone_number' => $request->drop_off_point_contact_phone_number,
			'serviceFee' => $request->serviceFee,
			'driverFee' => $request->driverFee,
			'polyline' => $request->polyline
		];

		session(['delivery_details' => $delivery_details]);

		// Store price details in session data

		// Store user data in session
		$user_details = [
			'name' => $request->name,
			'email' => $request->email,
			'country_code' => $request->country_code,
			'mobile' => $request->mobile
		];

		session(['user_details' => $user_details]);

        return redirect('/payments');
	}

    protected function addToOrdersTables($request, $error, $session_id = null) {
        // Insert into orders table
        $carts = session()->get('cart');
        $carts = json_decode(json_encode($carts));
		//dd($carts);
		$order = Order::create([
			'user_id' => auth()->user() ? auth()->user()->id : null,
			'name' => $request->name,
			'email' => $request->email,
			'mobile' => $request->mobile,
			'address' => $request->address,
			'country_code' => $request->mobile,
			'total' => $request->total,
			'error' => $error
		]);

		// Store order details in session
		$order ? session(['orderDetails' => ['orderId' => $order->id]]) : '';

		// Insert into orderProiduct table
		foreach ($carts as $item) {
			OrderProduct::create([
				'order_id' => $order->id,
				'product_id' => $item->id,
				'quantity' => $item->quantity,
				'price' => $item->price,
			]);
		}

		return $order;
	}
}
