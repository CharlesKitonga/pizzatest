<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Auth;
use Session;
use App\Cart;
use App\Category;
use App\Products_Attributes;
use App\Product;
use DB;

class ProductsController extends Controller {

    public function viewProducts(Request $request) {

        $products = Product::with('category')->get();
        $products = json_decode(json_encode($products));
        // foreach($products as $key => $val){
        //     $category_name = Category::where(['id'=>$val->category_id])->first();
        //     $products[$key]->category_name = $category_name->category_name;
        // }
        // echo "<pre>"; print_r($products); die;
        return view('admin.products.view_products')->with(compact('products'));
    }
    public function deleteAccompaniment($id = null) {
        Accompaniment::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Accompaniment has been Deleted Successfully!');
    }

    public function deleteProduct($id = null) {
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product has been Deleted Successfully!');
    }

    public function deleteSliderProduct($id = null) {
        SliderProducts::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product has been Deleted Successfully!');
    }

    public function deleteProductImage($id) {

        // Get Product Image
        $productImage = Product::where('id',$id)->first();

        // Get Product Image Paths#
        $orginal_image =  'storage/products/';
        $medium_image_path = 'storage/products/medium/';
        $small_image_path = 'storage/products/small/';

        // Delete Large Image if not exists in Folder
        if(file_exists($orginal_image.$productImage->image)){
            unlink($orginal_image.$productImage->image);
        }
        // Delete Medium Image if not exists in Folder
        if(file_exists($medium_image_path.$productImage->image)){
            @unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if not exists in Folder
        if(file_exists($small_image_path.$productImage->image)){
            @unlink($small_image_path.$productImage->image);
        }

        // Delete Image from Products table
        Product::where(['id'=>$id])->update(['image'=>'']);

        return redirect()->back()->with('flash_message_success', 'Product image has been deleted successfully');
    }


    public function products($id = null,$category_id='category_id') {

        //get main categories
        $mainCategories = Category::where(['parent_id'=>0])->get();

        //get sub categories
        $subCategories = Category::where(['parent_id' => $id])->get();
        //dd($subCategories);

        $allDetails = [];

        foreach ($subCategories as $subCategory) {
            $getCatNames = Category::with('products.attributes')->where(['id' =>$subCategory->id])->get();
            $getCatNames = json_decode(json_encode($getCatNames));
            //dd($getCatNames);
            array_push($allDetails, $getCatNames);

        }
        $checkProduct = Products_Attributes::get();
        //dd($checkProduct);
            // dd($allDetails);

        return view('frontpages/menu-list-collapse')->with(compact('mainCategories','allDetails','checkProduct'));
    }

    public function singleProduct($id = null, $category_id='category_id'){

        //get sub categories
        $subCategories = Category::where(['parent_id' => $id])->get();
        //dd($subCategories);

        $allDetails = [];

        foreach ($subCategories as $subCategory) {

            $getCatNames = Category::with('products.attributes')->where(['id' =>$subCategory->id])->get();
            $getCatNames = json_decode(json_encode($getCatNames));
            //dd($getCatNames);
            array_push($allDetails, $getCatNames);

        }
            //dd($allSpecificDetails);
        return view('frontpages/menu-single')->with(compact('subCategories','allDetails'));
    }

    public function getProductPrice(Request $request){
        $data = $request->all();
        //echo "<pre>";print_r($data);die;
        // $proArr = explode("-",$data['idSize']);
        //echo "<pre>";print_r($proArr);die;
        $proAttr = Products_Attributes::where(['id' => $data['idSize']])->get();

        return $proAttr[0] ? $proAttr[0] : null;
        // foreach ($proAttr as $pesa) {
        //    return $pesa->price;
        // }
        //dd($proAttr);
    }

    public function addToCart(Request $request, $id = 'id') {
        $data = $request->all();
        $data = json_decode(json_encode($data));
        // dd($data);
        if(!$data) {
            abort(404);
        }

        // Get all cart items
        $cart = session()->get('cart') ? session()->get('cart') : [];

        // dd($cart);

        // Use the size Id to get size information, if any
        if($data->size) {
            $sizeDetails = Products_Attributes::where(['id' => $data->size])->get();
        } else if($data->accompaniment) {
            $sizeDetails = Products_Attributes::where(['id' => $data->accompaniment])->get();
        } else {
            $sizeDetails = null;
        }
        // dd($sizeDetails);

        // Check if an item is already in the cart or not
        // Loop to check
        $cartCount = count($cart);
        $cartKeyMatch = null;

        // Return key
        foreach($cart as $key => $singleCartItem) {
            // Check if Ids match
            if($singleCartItem['id'] === $data->id) {
                // If they match, store the key value
                $cartKeyMatch = $key;
            }
        }

        if($cartKeyMatch) {
            // Check if accompaniments match
            if($cart[$cartKeyMatch]['accompaniment_id'] === $data->size) {
                // Edit particular entry by adding qty
                $cart[$cartKeyMatch]['quantity']++;

            } else {
                // Treat as a different product, proceed as normal
                $cart = $this->addCartItem($cart, $data, $sizeDetails);
            }

        } else {
            // Proceed as normal
            $cart = $this->addCartItem($cart, $data, $sizeDetails);
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function addCartItem($cart = [], $newItemData = null, $sizeDetails = null) {
        // Add item
        // Calculate price
        $price = $newItemData->price ?? 0;

        if($sizeDetails && count($sizeDetails) > 0) {
            // 1. Check if it is a size
            if($sizeDetails[0]->size) {
                $price = $sizeDetails[0]->price;
            } else {
                // 2. Check whether it is a priced accompaniment
                if($sizeDetails[0]->price) {
                    $price = $price + $sizeDetails[0]->price;
                }
            }
        }

        $cartItem = [
            "id" => $newItemData->id,
            "product_name" => $newItemData->product_name,
            "quantity" => $newItemData->quantity,
            "price" => $price,
            "accompaniment_id" => $sizeDetails && $sizeDetails[0]->id ? $sizeDetails[0]->id : null,
            "accompaniment_price" => $sizeDetails && $sizeDetails[0]->price ? $sizeDetails[0]->price : null,
            "accompaniment_size" => $sizeDetails && $sizeDetails[0]->size ? $sizeDetails[0]->size : null,
            "accompaniment" => $sizeDetails && $sizeDetails[0]->accompaniment ? $sizeDetails[0]->accompaniment : null
        ];

        // Use time as the cart Id since a cart can have 2 products with different sizes. They have to be treated as different products.
        $cart[time()] = $cartItem;

        // Return cart with new item innit
        return $cart;
    }

    public function updateCart(Request $request)
    {
        // Get input
        $quantity = $request->input('quantity');

        if($request->id){
            $cart = session()->get('cart');
            // dd($cart);
            if(isset($cart[$request->id])) {
                $cart[$request->id]['quantity'] = $quantity;
                // dd($cart);
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Product added to cart successfully!');
            }
        }
    }

    public function deleteCart(Request $request) {
        if($request->id) {
            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }
            return redirect()->back();
            session()->flash('success', 'Product removed successfully');
        }
    }



    public function search(Request $request) {

        $request->validate([
            'query' =>'required|min:3',
        ]);
        $query = $request->input('query');

                // one way of searching for a product
        $products = Product::where('product_name', 'like', "%$query%")
                        ->orwhere('description', 'like', "%$query%")->paginate(10);
        //different way using the searchable trait
        // $products = Product::search($query)->paginate(10);

        return view('search-results')->with(compact('products'));
    }
}
