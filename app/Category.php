<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable =[
        'id','category_name','parent_id','description','url','photo','status',
    ];
    protected $table = 'categories';

    public function subcategories(){

    	return $this->hasMany('App\Product','parent_id','id');

    }

    public function categories(){

        return $this->hasMany('App\Category','parent_id');
    }
    public function products(){

        return $this->hasMany('App\Product', 'category_id');
    }
    public function attributes(){
        return $this->hasMany('App\Products_Attributes','category_id');
    }
}
