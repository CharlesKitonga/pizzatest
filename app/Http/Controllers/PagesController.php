<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Order;
use Image;
use Auth;
use Session;
use App\Category;
use App\Products_Attributes;
use App\Accompaniment;
use App\Product;
use App\Products_Images;
use DB;

class PagesController extends Controller
{
    public function Index($id =null) {


        //In Descending Order yes
        $productsAll = Product::OrderBy('id','DESC')->simplePaginate(15);

        //In Random Order
        $productsAll = Product::inRandomOrder()->simplePaginate(15);

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
        return view('frontpages/index-burgers')->with(compact('productsAll','mainCategories','allDetails','checkProduct'));
    }

    public function Contact(Request $request) {

        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>";print_r($data);die;

            $contacts = New Contact;
            $contacts->name = $data['name'];
            $contacts->email = $data['email'];
            $contacts->subject = $data['subject'];
            $contacts->description = $data['description'];

            $contacts->save();

            return redirect('pages-about')->back()->with('flash_message_success','Message Send Successfully');
        }
        return view('frontpages/page-contact');
    }


    public function About() {
        return view('frontpages/page-about');
    }

    /* about us admin function*/
    public function AboutUs(Request $request){
        $data = $request->all();
        //dd($data);

        if ($request->isMethod('post')) {

            $companyDetails = new AboutUs;

            $companyDetails->heading = $data['heading'];
               //Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/company/large/'.$filename;
                    $medium_image_path = 'images/backend_images/company/medium/'.$filename;
                    $small_image_path = 'images/backend_images/company/small/'.$filename;
                    // Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(954,1125)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                }
            }

        }

        return view('admin/companyInfo/about-us');
    }
}
