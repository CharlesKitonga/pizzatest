@extends('layouts.frontLayout.front_unique_design')
@section('content')
<div id="content">
    <!-- Breadcrumbs -->
    <div class="page-title bg-dark dark">
        <!-- BG Image -->
        <div class="bg-image bg-parallax"><img src="assets/img/photos/bg-croissant.jpg" alt=""></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 push-lg-4">
                    <h1 class="mb-0">Account Details</h1>
                    <h4 class="text-muted mb-0">Keep your account details up-to-date</h4>
                </div>
            </div>
        </div>
    </div>

    <section class="section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-8">
                    <div class="bg-white p-4 p-md-18 mb-16">
                        <!-- Tab Panes -->
                        <div class="tab-content">
                            @if(Session::has('flash_message_error'))
                            <div class="alert alert-error alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{!! session('flash_message_error') !!}</strong>
                            </div>
                            @endif
                            @if(Session::has('flash_message_success'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{!! session('flash_message_success') !!}</strong>
                                </div>
                            @endif
                            <div class="utility-box">
                                <div class="utility-box-title bg-dark dark">
                                    <div class="bg-image">
                                        <img src="assets/img/photos/modal-review.jpg" alt="">
                                    </div>
                                    <div>
                                        <span class="icon icon-primary"><i class="ti ti-bookmark-alt"></i></span>
                                        <h4 class="mb-0">Update Account</h4>
                                    </div>
                                </div>
                                <form method="POST" id="accountForm" action="{{url('/account')}}" enctype="multipart/form-data" id="booking-form" data-validate>
                                    @csrf
                                    <div class="utility-box-content">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" style="" class="form-control" value="{{$userDetails->name}}"  id="name" name="name" placeholder="Full Name" required="">
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" value="{{$userDetails->address}}" id="address" name="address" class="address" placeholder="Address" required="">
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" value="{{$userDetails->city}}" id="city" name="city" placeholder="City" required="">
                                        </div>
                                        <div class="form-group">
                                            <label>Country Code</label>
                                            <select id="country" class="form-control">
                                                <option value="">Select Country Code</option>
                                                <option value="254">(+254) Kenya</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile Number <small>e.g. 711223344</small></label>
                                            <input type="tel" class="form-control" value="{{$userDetails->mobile}}" id="mobile" name="mobile" placeholder="Mobile" required="">
                                        </div>
                                    </div>
                                    <button class="utility-box-btn btn btn-secondary btn-block btn-lg btn-submit" type="submit">
                                        <span class="description">Update</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-content" style="margin-top: 50px;">
                            @if(Session::has('flash_message_error'))
                            <div class="alert alert-error alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{!! session('flash_message_error') !!}</strong>
                            </div>
                            @endif
                            @if(Session::has('flash_message_success'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{!! session('flash_message_success') !!}</strong>
                                </div>
                            @endif
                            <div class="utility-box">
                                <div class="utility-box-title bg-dark dark">
                                    <div class="bg-image">
                                        <img src="assets/img/photos/modal-review.jpg" alt="">
                                    </div>
                                    <div>
                                        <span class="icon icon-primary"><i class="ti ti-bookmark-alt"></i></span>
                                        <h4 class="mb-0">Change Password</h4>
                                    </div>
                                </div>
                                <form  id="passwordForm" method="post" name="passwordForm" action="{{url('/update-user-pwd')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="utility-box-content">
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <span id="chkPassword"></span>
                                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Current Password">
                                        </div>
                                        <div class="form-group">
                                            <label>New Password</label>
                                                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password">
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                                <input type="password" id="confirm_pwd" name="confirm_password" class="form-control" placeholder="Confirm Password">

                                        </div>
                                    </div>
                                    <button  name="Update Password" class="utility-box-btn btn btn-secondary btn-block btn-lg btn-submit" type="submit">
                                        <span class="description">Update Password</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection
