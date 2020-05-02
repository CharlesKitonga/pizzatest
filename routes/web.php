<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes(['verify' => true]);


Route::match(['get','post'], '/', 'PagesController@Index');
Route::match(['get','post'], '/contact', 'PagesController@Contact');
Route::get('invoice', function(){
    return view('frontpages/invoice');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//get navigation list
Route::match(['get','post'], 'CategoryController@viewMenu');
//get specific products based on category
Route::match(['get','post'], '/menu-list-navigation/{id}','ProductsController@products');
Route::match(['get','post'], '/menu-single/{id}','ProductsController@singleProduct');

//add to cart route
Route::match(['get','post'],'add-to-cart/{id}', 'ProductsController@addToCart');
Route::match(['get','post'],'/cart/update-cart/{id}', 'ProductsController@updateCart');
Route::match(['get','post'],'/cart/delete_cart/{id}','ProductsController@deleteCart');

//Register & Login Routes
Route::match(['get','post'], '/user-register', 'UserController@Register');
Route::match(['get','post'], '/user-login','UserController@Login');

//User Account Route with middleware
Route::group(['middleware'=>['Frontlogin','verified']], function() {

	Route::match(['get','post'], '/account', 'UserController@Account');
	Route::match(['get','post'], '/loginpage', 'UserController@loginPage');

	//order List
	Route::match(['get','post'],'/order-details','UserController@getOrders');
	//check current password
	Route::post('/check-user-pwd','UserController@CheckUserPwd');
	//Update current password in db
	Route::post('/update-user-pwd', 'UserController@updateUserPassword');

});

//User's logout
Route::get('/user-logout','UserController@logout');

//Checkout Page Route
Route::match(['get','post'], '/checkout','CheckoutController@guestCheckout');
Route::get('/guest-checkout','CheckoutController@guestCheckout');
Route::match(['get','post'], '/user-checkout','CheckoutController@Checkout')->middleware('auth');

//billing info route
Route::match(['get','post'],'/billing', 'CheckoutController@storeOrder');
Route::match(['get', 'post'], '/payments', 'PaymentsController@viewPayments');
Route::match(['get', 'post'], '/payments/process', 'PaymentsController@processPayments');
Route::get('/mailable', function() {
	$order = App\Order::find(3);

	return new App\Mail\OrderPlaced($order);
});



//rendering vue routes to the web
Route::get('{path}', 'HomeController@index')->where('path','([A-z\d-\/_.]+)?');
