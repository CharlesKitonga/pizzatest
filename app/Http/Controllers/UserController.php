<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Session;
use App\Order;
use App\DeliveryDetails;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\Frontlogin;

class UserController extends Controller
{

    public function Register(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>";print_r($data);die;
            $usersCount = User::where('email',$data['email'])->count();
            if ($usersCount>0) {
                return redirect()->back()->with('flash_message_error','Email has already been taken!');
            }else{
                $users = new User;
                $users->name = $data['name'];
                $users->email = $data['email'];
                $users->password = bcrypt($data['password']);
                $users->save();
                if (Auth::Attempt(['email'=>$data['email'], 'password'=>$data['password']])) {
                    Session::put('frontSession',$data['email']); 
                    return redirect('/user-checkout');
                }
            }
        }
        return view('users.register');
    }

    public function Login(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>";print_r($data);die;
            if (Auth::Attempt(['email'=>$data['email'],'password'=>$data['password']])) {
                Session::put('frontSession', $data['email']); 
                return redirect()->back();  
            }else{
                return redirect()->back()->with('flash_message_error', 'Invalid Username or Password');
            }
        }

    	return view('users.signin');
    }

    public function Account(Request $request){
        $user_id = Auth::User()->id;
        $userDetails = User::find($user_id);
        //echo "<pre>";print_r($userDetails);die;
       
       if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>";print_r($data);die;
 
            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->mobile = $data['mobile'];
            $user->save();

            return redirect()->back()->with('flash_message_success','Your Account Details have been Updated Succesfully');
       }
        return view('users.account')->with(compact('userDetails','orderDetails'));
    }

    public function getOrders(Request $request) {
        $user_id = Auth::User()->id;
        // 1. Get order + order items details
        $orderDetails = Order::with('orders')->where('user_id', $user_id)->orderBy('id', 'DESC')->get();

        // // 2. Get delivery details
        // $deliveryDetails = DeliveryDetails::where('order_id', );

        // // 3. Get payment details
        // $paymentDetails = ;

        return view('users.orders')->with(compact('orderDetails'));
    }

    public function CheckUserPwd(Request $request){
        $data = $request->all();
        //echo "<pre>";print_r($data);die;
        $current_password = $data['current_password'];
        $user_id = Auth::User()->id;
        $check_password = User::where('id',$user_id)->first();
        if(Hash::check($current_password,$check_password->password)){
            echo "true";die;
        }else{
            echo "false";die;
        }
    }
    public function updateUserPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>";print_r($data);die;
            $old_password = User::where('id',Auth::User()->id)->first();
            $current_password = $data['current_password'];
            if (Hash::check($current_password,$old_password->password)) {
                 //update password
                $new_password = bcrypt($data['new_password']);
                User::where('id', Auth::User()->id)->update(['password'=>$new_password]);
                 return redirect('/account')->with('flash_message_success','Password Updated Succesfully!');
            }else{
                return redirect('/account')->with('flash_message_error','Your Passwords do not Match!');
            }

        }
    }

    public function checkEmail(Request $request){
    	//check if user already exists
   		$data = $request->all();

    	$usersCount = User::where('email',$data['email'])->count();
    		if ($usersCount>0) {
    			echo "false";
    		}else{
    			echo "true";
    		}
    }

    public function loginPage(){
        return view ('users/loginpage');
    }

    public function registerPage(){

        return view('users/registerpage');
    }

    public function logout(){
        Auth::logout();
        Session::forget('frontSession');
        return view('users.logout')->with('flash_message_success','You have Succesfully logged out..');
		
    }
}
