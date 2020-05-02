<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;

class DispatchController extends Controller
{
    /**
     * Get list of all undispatched orders
     */
    public function getOrders(Request $request)
    {
        // Get variables
        $orderId = $request->orderId; // To get a single order
        $dispatchStatus = $request->dispatchStatus;

        // Get orders
        $query = DB::table('orders')
                        ->leftJoin('order_delivery_details', 'orders.id', '=', 'order_delivery_details.order_id')
                        ->leftJoin('order_payment_details', 'orders.id', '=', 'order_payment_details.order_id')
                        ->select('orders.*', 'order_delivery_details.delivery_reference', 'order_delivery_details.delivery_address', 'order_delivery_details.delivery_drop_off_coordinate', 'order_delivery_details.delivery_contact_phone_number', 'order_delivery_details.delivery_locality', 'order_delivery_details.delivery_charge', 'order_delivery_details.delivery_country', 'order_payment_details.payment_details_type', 'order_payment_details.payment_details_reference', 'order_payment_details.payment_details_processor_reference', 'order_payment_details.payment_details_amount', 'order_payment_details.payment_details_status', 'order_payment_details.payment_details_phone_number');

        // Check if order Id exists
        if($orderId) {
            $query->where('orders.id', $orderId);
        }

        if($dispatchStatus) {
            $query->where('orders.dispatch_status', $dispatchStatus);
        }

        // Get the fully paid one
        $query->where(function ($query) {
            $query->where('order_payment_details.payment_details_status', 'PAIDINFULL')
                ->orWhere('order_payment_details.payment_details_status', 'MORE');
        });

        try {
            if($orderId) {
                $orders = $query->first();
            } else {
                $orders = $query->get();
            }

            // Getting cart details
            for ($i = 0; $i < count($orders); $i++) {
                // Get cart and add it to order
                $queryTwo = DB::table('order_product')
                                ->leftJoin('products', 'order_product.product_id', '=', 'products.id')
                                ->leftJoin('products__attributes', 'order_product.accompaniment_id', '=', 'products__attributes.id')
                                ->select('order_product.product_id', 'order_product.quantity', 'order_product.price', 'order_product.accompaniment_id', 'products.product_name', 'products.description', 'products__attributes.accompaniment as products_attribute_accompaniment', 'products__attributes.size as product_attrubute_size', 'products__attributes.price as product_attrubute_price')
                                ->where('order_product.order_id', $orders[$i]->id);

                $orderProducts = $queryTwo->get();

                // Inject to order
                $orders[$i]->cart = $orderProducts;

            }

            $json = ['status' => 'success', 'data' => ['orders' => $orders]];
            $statusCode = 200;
        } catch (\Throwable $th) {
            $json = ['status' => 'error', 'message' => $th->getMessage() ?? 'There was an error retrieving order data.'];
            $statusCode = 400;
        }
        
        return response($json, $statusCode)
                    ->header('Content-Type', 'application/json');
    }

    /**
     * Get single order details
     */
    public function getOrderDetails(Request $request)
    {
        // Get params
        $orderId = $request->orderId;

        // Check if parameters have been passed
        $errorArray = [];

        if(!$orderId) {
            array_push($errorArray, ['name' => 'orderId', 'text' => 'The orderId variable is missing.']);
        }

        if(count($errorArray) > 0) {
            return response(['status' => 'error', 'message' => 'There are some missing parameters in your request.', 'list' => $errorArray], 400)
                        ->header('Content-Type', 'application/json');
        }

        // Get orders
        $query = DB::table('orders')
                        ->leftJoin('order_delivery_details', 'orders.id', '=', 'order_delivery_details.order_id')
                        ->leftJoin('order_payment_details', 'orders.id', '=', 'order_payment_details.order_id')
                        ->select('orders.*', 'order_delivery_details.delivery_reference', 'order_delivery_details.delivery_address', 'order_delivery_details.delivery_drop_off_coordinate', 'order_delivery_details.delivery_contact_phone_number', 'order_delivery_details.delivery_locality', 'order_delivery_details.delivery_charge', 'order_delivery_details.delivery_country', 'order_payment_details.payment_details_type', 'order_payment_details.payment_details_reference', 'order_payment_details.payment_details_processor_reference', 'order_payment_details.payment_details_amount', 'order_payment_details.payment_details_status', 'order_payment_details.payment_details_phone_number')
                        ->where('orders.id', $orderId);

        // Get the fully paid ones
        $query->where(function ($query) {
            $query->where('order_payment_details.payment_details_status', 'PAIDINFULL')
                ->orWhere('order_payment_details.payment_details_status', 'MORE');
        });

        // Get order
        try {
            $order = $query->first();

            // Check if there is information on the order obj
            if($order) {
                // Get order detail products
                $queryTwo = DB::table('order_product')
                                        ->leftJoin('products', 'order_product.product_id', '=', 'products.id')
                                        ->leftJoin('products__attributes', 'order_product.accompaniment_id', '=', 'products__attributes.id')
                                        ->select('order_product.product_id', 'order_product.quantity', 'order_product.price', 'order_product.accompaniment_id', 'products.product_name', 'products.description', 'products__attributes.accompaniment as products_attribute_accompaniment', 'products__attributes.size as product_attrubute_size', 'products__attributes.price as product_attrubute_price')
                                        ->where('order_product.order_id', $orderId);
                $orderProducts = $queryTwo->get();

                // Inject to order
                $order->cart = $orderProducts;

                // Return order
                $json = ['status' => 'success', 'data' => ['orders' => $order]];
                $statusCode = 200;

            } else {
                $json = ['status' => 'error', 'message' => 'There was no order with that ID found.'];
                $statusCode = 400;
            }

        } catch(\Throwable $th) {
            $json = ['status' => 'error', 'message' => $th->getMessage() ?? 'There was no order with that ID found.'];
            $statusCode = 400;
        }
        
        return response($json, $statusCode)
                    ->header('Content-Type', 'application/json');
    }

    /**
     * Dispatch order
     */
    public function dispatchOrder(Request $request)
    {
        // Get params
        $orderId = $request->orderId;

        // Check if parameters have been passed
        $errorArray = [];

        if(!$orderId) {
            array_push($errorArray, ['name' => 'orderId', 'text' => 'The orderId variable is missing.']);
        }

        if(count($errorArray) > 0) {
            return response(['status' => 'error', 'message' => 'There are some missing parameters in your request.', 'list' => $errorArray], 400)
                        ->header('Content-Type', 'application/json');
        }

        // Update order details
        try {
            $update = Order::where('id', $orderId)
                ->update(['dispatch_status' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
            $json = ['status' => 'success', 'data' => null];
            $statusCode = 200;
        } catch (\Throwable $th) {
            //throw $th;
            $json = ['status' => 'error', 'message' => $th->getMessage() ?? 'There was an error dispatching the order.'];
            $statusCode = 400;
        }
        
        return response($json, $statusCode)
                    ->header('Content-Type', 'application/json');
    }
}
