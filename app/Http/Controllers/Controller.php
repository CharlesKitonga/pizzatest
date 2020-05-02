<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Category;
use DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public static function categories(){
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();
        $categories = json_decode(json_encode($categories));
    	// echo "<pre>";print_r($categories);die;

    	return $categories;
    }
    public static function mobileMenu(){
        $mobileMenu = Category::where(['parent_id'=>0])->get();
        $mobileMenu = json_decode(json_encode($mobileMenu));
    	// echo "<pre>";print_r($mobileMenu);die;

    	return $mobileMenu;
	}
    public static function getMenu(){
        $getMenu = Category::where(['activate_categories'=>1])->get();
       // echo "<pre>";print_r($getMenu);

        return $getMenu;
    }
    public static function getMenuFirst(){
        $getMenuFirst = Category::where(['activate_categories'=>1])->first();
       // echo "<pre>";print_r($getMenuFirst);

        return $getMenuFirst;
    }
    public static function drinksMenu(){
        $drinksMenu = Category::where(['activate_categories'=>1])->first();
       // echo "<pre>";print_r($drinksMenu);

        return $drinksMenu;
    }

    public static function specificMenu(){
        $specificMenu = DB::table('categories')->where([
            ['parent_id', '=', '0'],
            ['activate_categories', '=', '0'],
        ])->get();
        // echo "<pre>";print_r($specificMenu);

        return $specificMenu;
    }
}
