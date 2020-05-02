<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\Products_Attributes;
use Illuminate\Support\Facades\Input;
use Image;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>";print_r($data);die;

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }

            if (empty($data['activate_categories'])) {
                $activate_categories = 0;
            }else{
                $activate_categories = 1;
            }
            $category = new Category;
            $category->category_name=$data['category_name'];
            $category->parent_id=$data['parent_id'];
            $category->description=$data['description'];

            $category->url=$data['url'];
            //Upload Image
            if ($request->hasFile('image')) {
                  $request->validate([
                            'image' => 'file|image|max:10000',
                        ]);
                    $storeImage = $request->image->store('categories', 'public');
                    $category->image = $storeImage;
            }
            $category->status = $status;
            $category->activate_categories = $activate_categories;
            //echo "<pre>";print_r($category);die;
            $category->save();
            return redirect('/admin/categories/view_category')->with('flash_message_success','Category Added Succesfully!!');
        }
        $levels = Category::where(['parent_id'=>0])->get();
        return view('/admin/categories.add_category')->with(compact('levels'));
    }

    public function editCategory(Request $request, $id=null){

        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>";print_r($data);die;
            if (empty($data['description'])) {
                $data['description'];
            }
            if(empty($data['status'])){
                $status='0';
            }else{
                $status='1';
            }

            if (empty($data['activate_categories'])) {
                $activate_categories = 0;
            }else{
                $activate_categories = 1;
            }
            //Upload Image
            if ($request->hasFile('image')) {
                $request->validate([
                          'image' => 'file|image|max:10000',
                      ]);
                  $storeImage = $request->image->store('products', 'public');
          }else{
              $storeImage = $data['current_image'];
          }            //echo "<pre>";print_r($filename);die;


            Category::where(['id'=>$id])->update(['category_name'=>$data['category_name'],'parent_id'=>$data['parent_id'],'description'=>$data['description'],'status'=>$status,'activate_categories'=>$activate_categories, 'image'=>$storeImage]);

            return redirect('admin/categories/view_category')->with('flash_message_success', 'Service has been Updated Successfully!');

        }

        $categoryDetails = Category::where(['id'=>$id])->first();
        $levels = Category::where(['parent_id'=>0])->get();

        return view('/admin/categories.edit_category')->with(compact('categoryDetails','levels'));
    }

    public function deleteCategory($id = null){
        if (!empty($id)) {
            Category::where(['id'=>$id])->delete();
            return redirect()->back()->with('flash_message_success','Category deleted Successfully!');
        }
   }

    public function viewCategories(Request $request){
        $categories = Category::get();
        $categories = json_decode(json_encode($categories));
        return view('/admin/categories.view_categories')->with(compact('categories'));
    }

}
