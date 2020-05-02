@extends('layouts.frontLayout.front_unique_design')

@section('content')

    <!-- Content -->
    <div id="content">

        <div class="jumbotron jumbotron-fluid menu-component">
            <h3 class="text-center menu-header">Checkout Now</h3>
        </div>

        <!-- Section -->
        <section class="section bg-light">

            <div class="container">
                <div class="row">
                    <div class="col-xl-4 push-xl-8 col-lg-5 push-lg-7 mr-auto">
                        <div class=" card shadow bg-white">
                            <div class="card-body">
                                <div class="row">
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
                                </div>
                                <div class="form-group">
                                    <label>Add special instruction</label>
                                    <textarea type="text" name="instructions" class="form-control" placeholder="Add description here">
                                </textarea>
                                </div>
                                <div class="cart-summary">
                                    {{--<div class="row">--}}
                                    {{--<div class="col-7 text-right text-muted">Order total:</div>--}}
                                    {{--<div class="col-5"><strong>Ksh. {{$total }}</strong></div>--}}
                                    {{--</div>--}}
                                    {{--<hr class="hr-sm" id="deliveryDetailsHr" >--}}
                                    <div class="row" id="deliveryChargeViewDiv">
                                        <div class="col-6 ml-auto">Delivery charge:  <strong></strong></div>
                                        <div class="col-6 mr-auto " id="deliveryChargeView">Delivery time:  <strong>0mins</strong></div>
                                    </div>
                                    <div class="row" id="deliveryDescriptionDiv" style="display: none;">
                                        <div class="col-12 text-muted" id="deliveryDescription"></div>
                                    </div>

                                    <div class="row text-md bottom-button">

                                        <div class="col-7 ml-auto" style="color: #fff;"><strong>Total</strong></div>
                                        <div class="col-5 mr-auto" id="totalDisplay" style="color: #fff;"><strong>Ksh. {{ $total }}</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>
                            <br>
                            <br>
                            <br>
                    <div class="col-xl-8 pull-xl-4 col-lg-7 pull-lg-5">
                        <div>
                            <form action="{{url('/billing')}}" method="POST" enctype="multipart/form-data" id="checkoutForm" data-validate>
                            @csrf
                                <h4 class="border-bottom pb-4"><i class="ti ti-user mr-3 text-primary"></i>Customer Information</h4>
                                <div>
                                    <div class="form-group">
                                        <label>Name:</label>
                                        <input type="text" name="name" class="form-control" value="{{ auth()->user() ? auth()->user()->name : '' }}" placeholder="e.g. John Doe" required>
                                    </div>
                                    {{-- <div class="form-group col-sm-6">
                                        <label>Address:</label>
                                        <input type="text" name="address" class="form-control">
                                    </div> --}}
                                    {{-- <div class="form-group col-sm-6">
                                        <label>City:</label>
                                        <input type="text" name="city"  class="form-control">
                                    </div> --}}
                                    <div class="form-group">
                                        <label>E-mail address:</label>
                                        <input type="email" name="email" class="form-control" value="{{ auth()->user() ? auth()->user()->email : '' }}" placeholder="e.g. johndoe@email.com" required>
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <label>Country Code:</label>
                                        <select class="form-control" name="country_code">
                                            <option value="254">Kenya (+254)</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <label>Phone number<small>(e.g. 711222333)</small>:</label>
                                        <input type="text" name="mobile" class="form-control" value="{{ auth()->user() ? auth()->user()->mobile : '' }}" placeholder="e.g. 711222333" minlength="9" maxlength="9">
                                    </div>
                                    {{-- <div class="text-center" style="margin-top: 35px; margin-left: 100px;" class="btn btn-blue btn-lg" >
                                        OR
                                        <a class="btn btn-info" href="#loginModal" data-toggle="modal" ><span>Login</span></a>
                                    </div> --}}
                                    @if(!auth()->user())
                                        {{--<div class="form-group col-md-12">--}}
                                            {{--<br />--}}
                                            {{--<p class="text-center">Already have an account? Click the button below to proceed.</p>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group col-md-12">--}}
                                            {{--<a class="btn btn-info" style="width: 100%;" href="#loginHeaderModal" data-toggle="modal" ><span>Login</span></a>--}}
                                        {{--</div>--}}
                                    @endif
                                </div>
                                    <br/>
                                    <br/>
                                <h4 class="border-bottom pb-4"><i class="ti ti-package mr-3 text-primary"></i>Delivery details</h4>
                                <div>
                                    {{-- <div class="form-group col-md-12">
                                        <label>Delivery time:</label>
                                        <div class="select-container">
                                            <select class="form-control" required>
                                                <option value="now">Now</option>
                                                <option value="schedule">Schedule for later</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="form-group col-md-12">
                                        <label>Scheduled Time:</label>
                                        <div class='input-group date' id='datetimepicker1'>
                                            <input type="text" name="scheduled_time" class="form-control" />
                                            <span class="input-group-addon">
                                                <i class="ti ti-calendar mr-3 text-primary"></i>
                                            </span>
                                        </div>
                                    </div> --}}
                                    <div class="form-group">
                                        <label>Drop-off location:</label>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="e.g. South C" required autocomplete="off" />
                                    </div>
                                    {{--<div class="form-group col-md-12">--}}
                                        {{--<label>Drop-off Description <small>e.g. Floor 2, Door 1</small>:</label>--}}
                                        {{--<input type="text" name="dropoff_point_description" class="form-control" placeholder="e.g. Floor 2, Door 1" required />--}}
                                    {{--</div>--}}
                                    <div class="form-group col-md-12">
                                    {{--<div class="form-group col-md-12">--}}
                                        {{--<label>Drop-off Description <small>e.g. Floor 2, Door 1</small>:</label>--}}
                                        {{--<input type="text" name="dropoff_point_description" class="form-control" placeholder="e.g. Floor 2, Door 1" required />--}}
                                    {{--</div>--}}
                                    <div class="form-group col-md-12">
                                    <div class="form-group col-md-12">
                                        <label>Drop-off Description <small>e.g. Floor 2, Door 1</small>:</label>
                                        <input type="text" name="dropoff_point_description" class="form-control" placeholder="e.g. Floor 2, Door 1" required />
                                    </div>
                                    <div class="form-group col-md-12">
                                    <div class="form-group">
                                        <label>Contact Phone Number <small>e.g. 0711222333</small>:</label>
                                        <input type="text" name="drop_off_point_contact_phone_number" class="form-control" placeholder="e.g. 0711222333" minlength="10" maxlength="10" required />
                                    </div>

                                    <input type="hidden" name="dropoff_cord1" id="dropoff_cord1" />
                                    <input type="hidden" name="country" id="country" />
                                    <input id="locality" name="locality" type="hidden" />
                                    <input id="deliveryPrice" name="deliveryPrice" type="hidden" />
                                    <input id="distance" name="distance" type="hidden" />
                                    <input id="duration" name="duration" type="hidden" />
                                    <input id="polyline" name="polyline" type="hidden" />
                                    <input id="delivery_description" name="delivery_description" type="hidden" />
                                    <input id="driverFee" name="driverFee" type="hidden" />
                                    <input id="serviceFee" name="serviceFee" type="hidden" />
                                    <input type="hidden" id="total" name="total" value="{{ $total }}" />
                                </div>
                                <br>
                                @if(session('cart'))
                                    <button class="utility-box-btn btn btn-primary btn-block btn-block btn-lg btn-submit" type="submit" id="orderSubmit">
                                        <span class="description">Proceed To Pay</span>
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
