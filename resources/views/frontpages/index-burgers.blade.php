@extends('layouts.frontLayout.front_unique_design')
@section('content')
<?php
use App\Http\Controllers\Controller;
$specificMenu = Controller::specificMenu();
$getMenu = Controller::getMenu();
$mobileMenu = Controller::mobileMenu();

?>
<!-- Content -->
<div id="content">

    <!-- Page Title -->
    <div class="jumbotron jumbotron-fluid menu-component">
        <h3 class="text-center menu-header">Order delicious Pizza dishes</h3>
        <h3 class="text-center menu-header">and get doorstep delivery</h3>
    </div>

    <!-- Page Content -->
    <div id="page-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row menu-flex-row">
                    <div class="col-3">
                        <h5 class="text-center"><b>Menu Available</b></h5>
                    </div>
                    <div class="col-8">

                        <div class="view-order">
                            <div class="row">
                                <div class="col-8">
                                    <a href="#" data-toggle="modal" data-target="#checkoutModal">
                                        <strong><i class="ti ti-shopping-cart" style="color: black;"> View Order</i></strong>
                                    </a>
                                </div>
                                <div class="col-4">
                                    @if(!empty(session('cart')))
                                        <p style="color:orange"><b>{{ count(session('cart')) }}</b></p>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Modal / Checkout -->
                <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Your Order</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <table class="table-cart">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th >Price</th>
                                        <th >Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <?php $total = 0 ?>
                                @if(session('cart'))
                                    @foreach(session('cart') as $id => $details)
                                    <?php $total += $details['price'] * $details['quantity']; ?>
                                    <tr>
                                        <td class="title">
                                            <span class="name">{{ $details['product_name'] }}</span><br/>

                                            <form action="{{url('/cart/update-cart/'.$id)}}" method="post">
                                                {!! csrf_field() !!}
                                                <input name="quantity" style="width: 50px;" type="number" value="{{ $details['quantity'] }}" class="name" min="1" max="10" /><br />
                                                @if(!empty($details['accompaniment']))
                                                    <span class="name"> + {{ $details['accompaniment'] }} {{ $details['accompaniment_price'] ? '(Ksh. ' . $details['accompaniment_price'] . ')' : '' }}</span>
                                                @endif
                                                @if(!empty($details['accompaniment_size']))
                                                    <span class="name">Size : {{$details['accompaniment_size']}}</span>
                                                @endif
                                        </td>
                                        <td id="getPrice" class="price">Ksh. {{ $details['price'] }} </td>
                                        <td id="itemTotal" class="price">Ksh. {{ $details['price'] * $details['quantity'] }} </td>
                                        <td class="actions">
                                                {{-- <a href="{{url('/cart/update-cart/'.$id)}}" class="action-icon" ><i class="fa fa-refresh"></i></a> --}}
                                                <button  class="action-icon" type="submit" style="background: none; padding: 0px; border: none;"><i style="color: #808080;"class="fa fa-refresh"></i></button>
                                                <br />
                                                {{-- <input type="hidden" name="cartId" value="{{$id}}" /> --}}
                                            </form>
                                            <a href="{{url('/cart/delete_cart/'.$id)}}" class="action-icon" ><i class="ti ti-close"></i></a>
                                        </td>

                                    </tr>
                                    @endforeach
                                @endif
                            </table>

                            <div class="row text-md bottom-button">
                                <div class="col-7 mr-auto"><strong><a href="{{url('/guest-checkout')}}" style="color: #fff;">Proceed to pay</a></strong></div>
                                <div class="col-5 mr-auto" id="totalDisplay" style="color: #fff;"><strong>Ksh. {{ $total }}</strong></div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="row menu-flex-row">
                    <div class="col-3 col-xs-12 order-first nav-side-menu">
                        <!-- Menu Navigation -->
                        <nav id="menu-navigation">
                            <ul class="nav nav-menu">
                                @foreach($specificMenu as $menu)
                                    <li><a href="{{url('/menu-list-navigation/'.$menu->id)}}" >{{$menu->category_name}}</a></li>
                                @endforeach
                            </ul>
                            <ul class="nav nav-menu">
                                @foreach($getMenu as $drinks)
                                    <li><a href="{{url('/menu-list-navigation/'.$drinks->id)}}" >{{$drinks->category_name}}</a></li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>

                    <div class="col-8 col-xs-12 order-last">
                        <div id="menuBurgersContent" class="menu-category" >
                            <!-- Menu Item -->
                            <div class="row menu-row">
                                @foreach($productsAll as $item)
                                    <div class="col-6">
                                        <div class="card menu-card">
                                            <div class="card-body">
                                                <div class="row justify-content-between">
                                                    <div class="col-7">
                                                        <h6 class="mb-0"><b>{{$item->product_name}}</b></h6>
                                                        <span
                                                            class="text-muted text-sm">{{$item->description}}</span>
                                                        <br>
                                                        <br>
                                                        <br>

                                                        <button type="button" class="btn btn-dark btn-xs"
                                                                data-target="#productModal{{$item->id}}"
                                                                data-toggle="modal"><span>Place order</span>
                                                        </button>
                                                    </div>
                                                    <div class="col-5 menu-image">
                                                        @if(!empty($item->photo))
                                                            <img class="img-fluid" src="{{ asset('images/products/'.$item->photo) }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                    <!-- Modal / Product -->
                                    <div class="modal fade hidden-lg" id="productModal{{$item->id}}" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header modal-header-lg dark bg-dark">
                                                    <div class="bg-image"><img src="{{asset('assets/img/photos/modal-add.jpg')}}" alt=""></div>
                                                    <h4 class="modal-title">Specify your dish</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
                                                </div>
                                                <div class="modal-product-details">
                                                    <div class="row align-items-center">
                                                        <div class="col-9">
                                                            <h6 class="mb-0">{{$item->product_name}}</h6>
                                                            <span class="text-muted">{{$item->description}}</span>
                                                        </div>
                                                        <span id="getPrice{{$item->id}}" class="text-md"> {{count($item->attributes) > 0 && $item->attributes[0]->size ? 'from' : '' }} Ksh. {{ count($item->attributes) > 0 && $item->attributes[0]->size ? $item->attributes[0]->size ? $item->attributes[0]->price ? $item->attributes[0]->price : 0 : 0 : $item->price}}</span>
                                                    </div>
                                                    <div class="modal-body panel-details-container">
                                                        <form name="addtocartForm" id="addtocartForm{{ $item->id }}" action="{{url('add-to-cart/'.$item->id)}}" method="post">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                            <input type="hidden" name="product_name" value="{{ $item->product_name }}">
                                                            <input type="hidden" name="price" id="price{{ $item->id }}" value="{{ $item->price }}">
                                                            <input type="hidden" name="size" value="">
                                                            <input type="hidden" name="accompaniment" value="">
                                                            <!-- Panel Details / Size -->
                                                            <div class="panel-details">
                                                                @if(count($item->attributes) > 0 && $item->attributes[0]->accompaniment)
                                                                    <h5 class="panel-details-title">
                                                                        Choose One Accompaniment
                                                                    </h5>
                                                                    <div>
                                                                        <div class="panel-details-content">
                                                                            <div class="form-group">
                                                                                @foreach($item->attributes as $getAccompaniment)
                                                                                    @if($getAccompaniment->accompaniment)
                                                                                        @if($getAccompaniment->product_id)
                                                                                            <label class="custom-control custom-radio">
                                                                                                <input name="accompaniment" value="{{ $getAccompaniment->id }}" type="radio" class="custom-control-input" required>
                                                                                                <span class="custom-control-indicator"></span>
                                                                                                <span class="custom-control-description">{{$getAccompaniment->accompaniment}} {{ $getAccompaniment->price ? ' ( + Ksh. ' . $getAccompaniment->price . ')' : '' }}</span>
                                                                                            </label>
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        {{-- {{ dd($item)}} --}}

                                                        <!-- Panel Details / Additions -->
                                                            <div class="panel-details">
                                                                @if(count($item->attributes) > 0 && $item->attributes[0]->size)
                                                                    <div class="row mb-5">
                                                                        <div class="form-group col-sm-12">
                                                                            {{-- <label>Delivery time:</label> --}}
                                                                            <div class="select-container">
                                                                                <select id="selSize{{ $item->id }}" name="size" class="form-control selSize" required>
                                                                                    <option value="">Select Size</option>
                                                                                    @foreach($item->attributes as $getSizes)
                                                                                        @if($getSizes->size)
                                                                                            <option value="{{$getSizes->id}}">{{ $getSizes->size }} {{ $getSizes->price ? '(Ksh. ' . $getSizes->price . ')' : '' }}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <br><br>
                                                            <div class="col-4">
                                                                <input name="quantity" style=" margin-left: 10px; width: 90px; height:30px;" type="number" value="1" class="name" min="1" max="10"/>
                                                            </div>
                                                            <div class="modal-footer button-container">
                                                                <div class="mr-auto">
                                                                    <button type="submit" class="btn btn-dark" >Continue Ordering</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
    <div id="mobile">
        <div class="container">
            <div class="row mobile-row">
                <div class="col-6">
                    <div class="dropdown">
                        <button class="btn btn-dark  dropdown-toggle mobile-button "
                                data-toggle="dropdown" type="button"
                                id="dropdownMenuButton"
                                aria-haspopup="true" aria-expanded="false">
                            View menu

                        </button>
                        <!-- Menu dropdown-->

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @foreach($mobileMenu as $menu)
                            <li class="dropdown-item"><a href="{{url('/menu-list-navigation/'.$menu->id)}}" >{{$menu->category_name}}</a></li>
                        @endforeach
                    </ul>
                    </div>
                </div>
                <div>
                        <div class="view-order">
                            <div class="row">
                               <div class="col-8">
                                    <p class="view-text btn btn-light" data-target="#checkoutModalsmall"
                                       data-toggle="modal">
                                        <i class="ti ti-shopping-cart"></i>
                                        View order
                                    </p>
                                </div>
                                <div class="col-4">
                                    @if(!empty(session('cart')))
                                        <p style="color:orange; text-align: center"><b>{{ count(session('cart')) }}</b></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    <div class="modal fade hidden-lg" id="checkoutModalsmall" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Your Order</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table-cart">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th >Price</th>
                                                <th >Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php $total = 0 ?>
                                        @if(session('cart'))
                                            @foreach(session('cart') as $id => $details)
                                            <?php $total += $details['price'] * $details['quantity']; ?>
                                            <tr>
                                                <td class="title">
                                                    <span class="name">{{ $details['product_name'] }}</span><br/>

                                                    <form action="{{url('/cart/update-cart/'.$id)}}" method="post">
                                                        {!! csrf_field() !!}

                                                        <input name="quantity" style="width: 50px;" type="number" value="{{ $details['quantity'] }}" class="name" min="1" max="10" /><br />
                                                        @if(!empty($details['accompaniment']))
                                                            <span class="name"> + {{ $details['accompaniment'] }} {{ $details['accompaniment_price'] ? '(Ksh. ' . $details['accompaniment_price'] . ')' : '' }}</span>
                                                        @endif
                                                        @if(!empty($details['accompaniment_size']))
                                                            <span class="name">Size : {{$details['accompaniment_size']}}</span>
                                                        @endif
                                                </td>
                                                <td id="getPrice" class="price">Ksh. {{ $details['price'] }} </td>
                                                <td id="itemTotal" class="price">Ksh. {{ $details['price'] * $details['quantity'] }} </td>
                                                <td class="actions">
                                                        {{-- <a href="{{url('/cart/update-cart/'.$id)}}" class="action-icon" ><i class="fa fa-refresh"></i></a> --}}
                                                        <button  class="action-icon" type="submit" style="background: none; padding: 0px; border: none;"><i style="color: #808080;"class="fa fa-refresh"></i></button>
                                                        <br />
                                                        {{-- <input type="hidden" name="cartId" value="{{$id}}" /> --}}
                                                    </form>
                                                    <a href="{{url('/cart/delete_cart/'.$id)}}" class="action-icon" ><i class="ti ti-close"></i></a>
                                                </td>

                                            </tr>
                                            @endforeach
                                        @endif
                                    </table>

                                    <div class="row text-md bottom-button">
                                        <div class="col-7 mr-auto"><strong><a href="{{url('/guest-checkout')}}" style="color: #fff;">Proceed to pay</a></strong></div>
                                        <div class="col-5 mr-auto" id="totalDisplay" style="color: #fff;"><strong>Ksh. {{ $total }}</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <br>
            <br>
            <div id="menuBurgersContent" class="menu-category">
                        <div class="row d-flex">
                        <!-- Menu Item -->
                            @foreach($productsAll as $item)
                                <div class="col-xs-12  col-sm-6 mobile-column">
                                    <div class="card menu-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h6 class="mb-0"><b>{{$item->product_name}}</b></h6>
                                                    <span
                                                        class="text-muted text-sm">{{$item->description}}</span>
                                                    <br>
                                                    <br>
                                                    <br>

                                                    <button type="button" class="btn btn-dark btn-xs"
                                                            data-target="#productModalsmall{{$item->id}}"
                                                            data-toggle="modal"><span>Place order</span>
                                                    </button>
                                                    <div class="modal fade hidden-lg" id="productModal{{$item->id}}" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-lg dark bg-dark">
                                                                    <div class="bg-image"><img src="{{asset('assets/img/photos/modal-add.jpg')}}" alt=""></div>
                                                                    <h4 class="modal-title">Specify your dish</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
                                                                </div>
                                                                <div class="modal-product-details">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-9">
                                                                            <h6 class="mb-0">{{$item->product_name}}</h6>
                                                                            <span class="text-muted">{{$item->description}}</span>
                                                                        </div>
                                                                        <span id="getPrice{{$item->id}}" class="text-md"> {{count($item->attributes) > 0 && $item->attributes[0]->size ? 'from' : '' }} Ksh. {{ count($item->attributes) > 0 && $item->attributes[0]->size ? $item->attributes[0]->size ? $item->attributes[0]->price ? $item->attributes[0]->price : 0 : 0 : $item->price}}</span>
                                                                    </div>
                                                                    <div class="modal-body panel-details-container">
                                                                        <form name="addtocartForm" id="addtocartForm{{ $item->id }}" action="{{url('add-to-cart/'.$item->id)}}" method="post">
                                                                            {!! csrf_field() !!}
                                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                                            <input type="hidden" name="product_name" value="{{ $item->product_name }}">
                                                                            <input type="hidden" name="price" id="price{{ $item->id }}" value="{{ $item->price }}">
                                                                            <input type="hidden" name="size" value="">
                                                                            <input type="hidden" name="accompaniment" value="">
                                                                            <!-- Panel Details / Size -->
                                                                            <div class="panel-details">
                                                                                @if(count($item->attributes) > 0 && $item->attributes[0]->accompaniment)
                                                                                    <h5 class="panel-details-title">
                                                                                        Choose One Accompaniment
                                                                                    </h5>
                                                                                    <div>
                                                                                        <div class="panel-details-content">
                                                                                            <div class="form-group">
                                                                                                @foreach($item->attributes as $getAccompaniment)
                                                                                                    @if($getAccompaniment->accompaniment)
                                                                                                        @if($getAccompaniment->product_id)
                                                                                                            <label class="custom-control custom-radio">
                                                                                                                <input name="accompaniment" value="{{ $getAccompaniment->id }}" type="radio" class="custom-control-input" required>
                                                                                                                <span class="custom-control-indicator"></span>
                                                                                                                <span class="custom-control-description">{{$getAccompaniment->accompaniment}} {{ $getAccompaniment->price ? ' ( + Ksh. ' . $getAccompaniment->price . ')' : '' }}</span>
                                                                                                            </label>
                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            </div>

                                                                        {{-- {{ dd($item)}} --}}

                                                                        <!-- Panel Details / Additions -->
                                                                            <div class="panel-details">
                                                                                @if(count($item->attributes) > 0 && $item->attributes[0]->size)
                                                                                    <div class="row mb-5">
                                                                                        <div class="form-group col-sm-12">
                                                                                            {{-- <label>Delivery time:</label> --}}
                                                                                            <div class="select-container">
                                                                                                <select id="selSize{{ $item->id }}" name="size" class="form-control selSize" required>
                                                                                                    <option value="">Select Size</option>
                                                                                                    @foreach($item->attributes as $getSizes)
                                                                                                        @if($getSizes->size)
                                                                                                            <option value="{{$getSizes->id}}">{{ $getSizes->size }} {{ $getSizes->price ? '(Ksh. ' . $getSizes->price . ')' : '' }}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="modal-footer button-container">
                                                                                <div class="mr-auto">
                                                                                    <button type="submit" class="btn btn-dark" >Continue Ordering</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-5 menu-image">
                                                    @if(!empty($item->photo))
                                                        <img src="{{asset('images/products/'.$item->photo) }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <!-- Modal / Product -->
                                <div class="modal fade hidden-lg" id="productModalsmall{{$item->id}}" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-lg dark bg-dark">
                                                <div class="bg-image"><img src="{{asset('assets/img/photos/modal-add.jpg')}}" alt=""></div>
                                                <h4 class="modal-title">Specify your dish</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
                                            </div>
                                            <div class="modal-product-details">
                                                <div class="row align-items-center">
                                                    <div class="col-9">
                                                        <h6 class="mb-0">{{$item->product_name}}</h6>
                                                        <span class="text-muted">{{$item->description}}</span>
                                                    </div>
                                                    <span id="getPrice{{$item->id}}" class="text-md"> {{count($item->attributes) > 0 && $item->attributes[0]->size ? 'from' : '' }} Ksh. {{ count($item->attributes) > 0 && $item->attributes[0]->size ? $item->attributes[0]->size ? $item->attributes[0]->price ? $item->attributes[0]->price : 0 : 0 : $item->price}}</span>
                                                </div>
                                                <div class="modal-body panel-details-container">
                                                    <form name="addtocartForm" id="addtocartForm{{ $item->id }}" action="{{url('add-to-cart/'.$item->id)}}" method="post">
                                                        {!! csrf_field() !!}
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <input type="hidden" name="product_name" value="{{ $item->product_name }}">
                                                        <input type="hidden" name="price" id="price{{ $item->id }}" value="{{ $item->price }}">
                                                        <input type="hidden" name="size" value="">
                                                        <input type="hidden" name="accompaniment" value="">
                                                        <!-- Panel Details / Size -->
                                                        <div class="panel-details">
                                                            @if(count($item->attributes) > 0 && $item->attributes[0]->accompaniment)
                                                                <h5 class="panel-details-title">
                                                                    Choose One Accompaniment
                                                                </h5>
                                                                <div>
                                                                    <div class="panel-details-content">
                                                                        <div class="form-group">
                                                                            @foreach($item->attributes as $getAccompaniment)
                                                                                @if($getAccompaniment->accompaniment)
                                                                                    @if($getAccompaniment->product_id)
                                                                                        <label class="custom-control custom-radio">
                                                                                            <input name="accompaniment" value="{{ $getAccompaniment->id }}" type="radio" class="custom-control-input" required>
                                                                                            <span class="custom-control-indicator"></span>
                                                                                            <span class="custom-control-description">{{$getAccompaniment->accompaniment}} {{ $getAccompaniment->price ? ' ( + Ksh. ' . $getAccompaniment->price . ')' : '' }}</span>
                                                                                        </label>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                    {{-- {{ dd($item)}} --}}

                                                    <!-- Panel Details / Additions -->
                                                        <div class="panel-details">
                                                            @if(count($item->attributes) > 0 && $item->attributes[0]->size)
                                                                <div class="row mb-5">
                                                                    <div class="form-group col-sm-12">
                                                                        {{-- <label>Delivery time:</label> --}}
                                                                        <div class="select-container">
                                                                            <select id="selSize{{ $item->id }}" name="size" class="form-control selSize" required>
                                                                                <option value="">Select Size</option>
                                                                                @foreach($item->attributes as $getSizes)
                                                                                    @if($getSizes->size)
                                                                                        <option value="{{$getSizes->id}}">{{ $getSizes->size }} {{ $getSizes->price ? '(Ksh. ' . $getSizes->price . ')' : '' }}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <div class="col-4">
                                                            <input name="quantity" style=" margin-left: 10px; width: 90px; height:30px;" type="number" value="1" class="name" min="1" max="10"/>
                                                        </div>
                                                        <div class="modal-footer button-container">
                                                            <div class="mr-auto">
                                                                <button type="submit" class="btn btn-dark" >Continue Ordering</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

            </div>


        </div>
    </div>
    <div id="body-overlay"></div>
@endsection
