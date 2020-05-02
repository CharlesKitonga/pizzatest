<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Auth;
use Session;
use App\Category;
use App\Products_Attributes;
use App\Product;
use App\SliderProducts;
use App\Products_Images;

class IndexController extends Controller
{
    public function addSliderImages(Request $request){

    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		//echo "<pre>";print_r($data); die;
    		if(empty($data['parent_id'])){
    			return redirect()->back()->with('flash_message_error','Under Category is missing!');	
    		}
 			$product = new SliderProducts;

            $product->parent_id = $data['parent_id'];
            $product->product_id = $data['product_id'];
            if (!empty($data['description'])) {
                $product->description = $data['description'];
            }else{
                $product->description = ''; 
            }
 			$product->caption =$data['caption'];

 			//Upload Image
 			if ($request->hasFile('image')) {
 				$image_tmp =    $request->validate([
                            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10000',
                        ]);
  
                    //$image_tmp = json_decode(json_encode($image_tmp));
 					$extension = time().'.'.$request->image->extension();
                    $filename = rand(111,99999).'.'.$extension;
    			    $storeImage = $request->image->move(public_path('images/backend_images/sliderImages'), $filename);
    				// Store image name in products table
    				$product->image = $filename;
 	
 			}
            
 			$product->save();
 			return redirect()->back()->with('flash_message_success','Product Added Successfuly!');
    	}

    	$categories = Category::where(['parent_id'=>0])->get();
    	$categories_dropdown = "<option value='' selected disabled>Select</option>";
    	foreach($categories as $cat){
    		$categories_dropdown .= "<option value='".$cat->id."'>".$cat->category_name."</option>";
    		$sub_categories = Category::where(['parent_id'=>$cat->id])->get();
    		foreach ($sub_categories as $sub_cat) {
    			$categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->category_name."</option>";
    		}
    	}
        $getProducts = Product::get();
        $category_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($getProducts as $product){
            $category_dropdown .= "<option value='".$product->id."'>".$product->product_name."</option>";
        }
    	return view('admin.products.add_slider_images')->with(compact('categories_dropdown','category_dropdown'));
    }

    public function editSliderProduct(Request $request, $id='id'){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>";print_r($data);die;
             //Upload Image
            if ($request->hasFile('image')) {
                $image_tmp =    $request->validate([
                            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10000',
                        ]);
  
                    //$image_tmp = json_decode(json_encode($image_tmp));
                    $extension = time().'.'.$request->image->extension();
                    $filename = rand(111,99999).'.'.$extension;
                    $storeImage = $request->image->move(public_path('images/backend_images/sliderImages'), $filename);
                    // Store image name in products table
                    $product->image = $filename;
    
            }else{
                $filename = $data['current_image'];
            }

            if (empty($data['description'])) {
                $data['description'];
            }
            SliderProducts::where(['id'=>$id])->update(['parent_id'=>$data['parent_id'],'product_id'=>$data['product_id'],'caption'=>$data['caption'],'description'=>$data['description'],'image'=>$filename]);

           //dd($test);die;

            return redirect('/admin/products/view_slider_products')->with('flash_message_success', 'Product has been Updated Successfully!');

        }
        $productDetails = SliderProducts::where(['id'=>$id])->first();
        //dd($productDetails);
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->category_name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                $categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->category_name."</option>";
            }
        }
        $getProducts = Product::get();
        $category_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($getProducts as $product){
            $category_dropdown .= "<option value='".$product->id."'>".$product->product_name."</option>";
        }
        return view('admin.products.edit_slider_products')->with(compact('categories_dropdown','category_dropdown','productDetails'));

    }

    public function viewProducts(){

        $products = SliderProducts::get();
        $products = json_decode(json_encode($products));

        // echo "<pre>"; print_r($products); die;
        return view('admin.products.view_slider_products')->with(compact('products'));
    }
    public function deleteSliderProduct($id = null) {
        SliderProducts::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product has been Deleted Successfully!');
    }
        public function deleteSliderImage($id) {

        // Get Product Image
        $productImage = Product::where('id',$id)->first();

        // Get Product Image Paths
        $large_image_path = 'images/backend_images/sliderImages/';
     

        // Delete Large Image if not exists in Folder
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }

        // Delete Image from Products table
        SliderProducts::where(['id'=>$id])->update(['image'=>'']);

        return redirect()->back()->with('flash_message_success', 'Product image has been deleted successfully');
    }
}
//
