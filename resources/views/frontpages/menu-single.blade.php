@extends('layouts.frontLayout.front_design')
@section('content')

<!-- Content -->
<div id="content">

    <!-- Page Title -->
    <div class="page-title bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 push-lg-4">
                    <h1 class="mb-0">Menu List</h1>
                    <h4 class="text-muted mb-0">Some informations about our restaurant</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-md-8 push-md-1" role="tablist">
                    <!-- Menu Category / Burgers -->
                    @foreach($allDetails as $category)
                        @if(count($category) > 0 && $category[0]->id)
                            @if(count($category[0]->products) > 0)
                                <div id="Burgers" class="menu-category">
                                    <div class="menu-category-title collapse-toggle" role="tab" data-target="#menuBurgersContent<?php echo($category[0]->id);?>" data-toggle="collapse" aria-expanded="true">
                                        @if(!empty($category[0]->photo))
                                            <div class="bg-image"><img src="{{ asset('/images/backend_images/categories/medium/'.$category[0]->image) }}" alt=""></div>
                                        @elseif(empty($category[0]->photo))
                                            <div class="bg-image"><img src="{{ asset('/images/backend_images/categories/medium/'.$category[0]->image) }}" alt=""></div>
                                        @endif
                                        <h2 style="font-weight: bold;" class="title"><?php echo($category[0]->category_name);?></h2>
                                    </div>
                                    <div id="menuBurgersContent<?php echo ($category[0]->id);?>" class="menu-category-content collapse show">
                                        <!-- Menu Item -->
                                        @foreach($category[0]->products as $item)
                                            @if($item->id)
                                                <div class="menu-item menu-list-item">
                                                    <div class="row align-items-center">
                                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                                            <h6 class="mb-0">{{$item->product_name}}</h6>
                                                            <span class="text-muted text-sm">{{$item->description}}</span>
                                                        </div>
                                                        <div class="col-sm-6 text-sm-right">
                                                            <!-- If there are sizes, check the smallest -->
                                                            <span class="text-md"><span class="text-muted">{{count($item->attributes) > 0 && $item->attributes[0]->size ? 'from' : '' }}</span> Ksh. {{ count($item->attributes) > 0 && $item->attributes[0]->size ? $item->attributes[0]->size ? $item->attributes[0]->price ? $item->attributes[0]->price : 0 : 0 : $item->price}}</span>
                                                            <button class="btn btn-outline-secondary btn-sm" data-target="#productModal{{$item->id}}" data-toggle="modal"><span>View Product</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal / Product -->
                                                <div class="modal fade" id="productModal{{$item->id}}" role="dialog">
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
                                                                <button type="submit" class="btn btn-outline-secondary btn-sm btn-block btn-lg"><span> Add to Cart</span></button>
                                                            </form>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content / End -->
<!-- Body Overlay -->
<div id="body-overlay"></div>

@endsection
