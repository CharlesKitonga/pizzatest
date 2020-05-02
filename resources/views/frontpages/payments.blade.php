@extends('layouts.frontLayout.front_unique_design')

@section('content')

    <!-- Content -->
    <div id="content">

        <!-- Page Title -->

    <div class="jumbotron jumbotron-fluid menu-component">

        <h3 class="text-center menu-header">Pay via mpesa or cash on delivery</h3>
    </div>


        <!-- Section -->
        <section class="section bg-light">

            {{-- {{dd(session('delivery_details'))}} --}}

            <div class="container">
                <div class="row">
                    <div class="col-xl-4 push-xl-8 col-lg-5 push-lg-7 mr-auto">
                        <div class=" card shadow bg-white">
                            <div class="bg-dark dark p-4"><h5 class="mb-0 card-title">Your order</h5></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <p class="caption ">Quantity </p>
                                    </div>
                                    <div class="col-4">
                                        <p class="name"><a href="#" >Name </a></p>

                                    </div>
                                    <div class="col-4">
                                        <p class="name"><a href="#" >Price</a></p>
                                    </div>

                                </div>
                                <div class="row">
                                    <?php $total = 0; ?>

                                    @if(session('cart'))
                                        @foreach(session('cart') as $id => $details)
                                            <?php $total += $details['price'] * $details['quantity'] ?>

                                            <div class="col-4">

                                                <div class="form-group">
                                                    <span class="caption text">Qty : {{ $details['quantity'] }}</span>
                                                </div>

                                            </div>
                                            <div class="col-4">
                                                <p class="name"><a href="#" >{{ $details['product_name'] }}</a></p>
                                            </div>
                                                {{--@if(!empty($details['accompaniment']))--}}
                                                    {{--<span class="name"> + {{ $details['accompaniment'] }} 
                                                        {{ $details['accompaniment_price'] ? '($. '.$details['accompaniment_price'] . ')' : '' }}
                                                    </span>--}}
                                                {{--@endif--}}
                                            <br />
                                            @if(!empty($details['accompaniment_size']))
                                                <span class="name">Size : {{$details['accompaniment_size']}}</span>
                                            @endif
                                            <div class="col-4">
                                                <div class=" price">$. {{ $details['price'] }}</div>
                                                {{--<div class="price">$. {{ $details['price'] * $details['quantity'] }}</div>--}}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="cart-summary">
                                    <div class="row">
                                        <div class="col-7 text-right text-muted">Order total:</div>
                                        <div class="col-5"><strong>$. {{$total }}</strong></div>
                                    </div>
                                    <hr class="hr-sm" id="deliveryDetailsHr">
                                    <div class="row" id="deliveryChargeViewDiv">
                                        <div class="col-7 text-right text-muted">Delivery charge:</div>
                                    <div class="col-5" id="deliveryChargeView"><strong>$. {{ session('delivery_details') ? session('delivery_details')['deliveryPrice'] : 0 }}</strong></div>
                                    </div>
                                    <div class="row" id="deliveryDescriptionDiv">
                                        <div class="col-12 text-muted" id="deliveryDescription"><center><small>{{ session('delivery_details') ? 'Delivery time: ' . session('delivery_details')['duration'] . ' minute(s), Delivery distance: ' . session('delivery_details')['distance'] . ' km(s)' : '' }}</small></center></div>
                                    </div>
                                    <hr class="hr-sm">
                                    <div class="row text-md">
                                        <div class="col-7 text-right text-muted">Total:</div>
                                        <div class="col-5" id="totalDisplay"><strong>$. {{ session('delivery_details') ? ((int) session('delivery_details')['deliveryPrice'] + (int) $total) : $total }}</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 pull-xl-4 col-lg-7   pull-lg-5">
                        <div class="bg-white p-4 p-md-5 mb-4">
                            <form action="{{url('/payments/process')}}" method="POST" enctype="multipart/form-data" id="paymentForm" data-validate>
                            @csrf
                                <h4 class="border-bottom pb-4"><i class="ti ti-wallet mr-3 text-primary"></i>Payment</h4>
                                <div class="row text-lg"><!-- 
                                    <div class="col-6  form-group">
                                        <label class="custom-control custom-radio">
                                            <input type="radio" name="payment_type" value="mpesa" class="custom-control-input" checked="checked" id="payment_type" required>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Mpesa</span>
                                        </label> -->
                                    </div>
                                    <div class="col-6 form-group">
                                        <label class="custom-control custom-radio">
                                            <input type="radio" name="payment_type" value="cash" class="custom-control-input" required>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Cash</span>
                                        </label>
                                    </div>
                                </div><!-- 

                                <div class="row text-lg" id="mpesaContent">
                                    <div class="form-group col-md-12" id="mpesa-extra-details">
                                        <div class="alert alert-light">
                                            <span>
                                                MPESA (for account Kilimanjaro Jamia) will send a payment request to the number entered below. Once you receive the request, just enter your MPESA pin to complete the transaction. Please keep your phone screen on to easily receive the payment request.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Phone Number <small>(e.g. 0700112233)</small></label>
                                        <input type="text" name="phoneNumber" id="paymentPhoneNumber" class="form-control" placeholder="e.g. 0711223344" required autocomplete="off" minlength="10" maxlength="10" value="{{ session('user_details') ? session('user_details')['mobile'] ? ('0' . session('user_details')['mobile']) : '' : '' }}" />
                                    </div>
                                </div> -->

                                <div class="row text-lg" id="cashContent">
                                    <div class="form-group col-md-12">
                                        <div class="alert alert-info">
                                            <span>
                                                Pay cash on delivery once your food is delivery.
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if(session('cart'))
                                    <button class="utility-box-btn btn btn-dark btn-block btn-block btn-lg btn-submit" type="submit" id="paymentSubmit">
                                        <span class="description">Complete Payment</span>
                                        <span class="success">
                                            <svg x="0px" y="0px" viewBox="0 0 32 32"><path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"/></svg>
                                        </span>
                                        <span class="error">Try again...</span>
                                    </button>
                                @else
                                    <button class="utility-box-btn btn btn-danger btn-block btn-block btn-lg btn-submit" type="submit" id="orderSubmit">
                                        <span class="description">Please add an item to your cart first.</span>
                                        <span class="success">
                                            <svg x="0px" y="0px" viewBox="0 0 32 32"><path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"/></svg>
                                        </span>
                                        <span class="error">Try again...</span>
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </div>

@endsection
