<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Meta -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"  content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Title -->
    <title>Pizza Task</title>

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}">
    <link rel="apple-touch-icon" href="{{asset('assets/img/favicon_60x60.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/favicon_76x76.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('assets/img/favicon_120x120.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('assets/img/favicon_152x152.png')}}">

    <!-- CSS Plugins -->
    <link rel="stylesheet" href="{{asset('plugins/bootstrap/dist/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('plugins/slick-carousel/slick/slick.css')}}" />
    <link rel="stylesheet" href="{{asset('plugins/animate.css/animate.min.css')}}" />
    <link rel="stylesheet" href="{{asset('plugins/animsition/dist/css/animsition.min.css')}}" />
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> --}}

<!-- CSS Icons -->
    <link rel="stylesheet" href="{{asset('css/themify-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('plugins/font-awesome/css/font-awesome.min.css')}}" />

    <!-- CSS Theme -->
    <link id="theme" rel="stylesheet" href="{{asset('css/themes/theme-beige.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" integrity="sha256-yMjaV542P+q1RnH6XByCPDfUFhmOafWbeLPmqKh11zo=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha256-ENFZrbVzylNbgnXx0n3I1g//2WeO47XxoPe0vkp3NC8=" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.css"/>

</head>

<body>

<!-- Body Wrapper -->
<div id="body-wrapper" class="animsition">
@include('layouts.frontLayout.design_header')
@include('layouts.frontLayout.cart')

@yield('content')

@include('layouts.frontLayout.footer')

<!-- Body Overlay -->
    <div id="body-overlay"></div>

</div>

<!-- Video Modal / Demo -->
<div class="modal modal-video fade" id="modalVideo" role="dialog">
    <button class="close" data-dismiss="modal"><i class="ti-close"></i></button>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <iframe height="500"></iframe>
        </div>
    </div>
</div>

<!-- Panel Cart -->
<div id="panel-cart">
    <div class="panel-cart-container">
        <div class="panel-cart-title">
            <h5 class="title">Your Cart</h5>
            <button class="close" data-toggle="panel-cart"><i class="ti ti-close"></i></button>
        </div>
        <div class="panel-cart-content">
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
                                <span class="name">Name : {{ $details['product_name'] }}</span><br/>

                                <form action="{{url('/cart/update-cart/'.$id)}}" method="post">
                                    {!! csrf_field() !!}
                                    <input name="quantity" style="width: 50px;" type="number" value="{{ $details['quantity'] }}" class="name" min="1" max="10" /><br />
                                    @if(!empty($details['accompaniment']))
                                        <span class="name"> + {{ $details['accompaniment'] }} {{ $details['accompaniment_price'] ? '($. ' . $details['accompaniment_price'] . ')' : '' }}</span>
                                    @endif
                                    @if(!empty($details['accompaniment_size']))
                                        <span class="name">Size : {{$details['accompaniment_size']}}</span>
                                @endif
                            </td>
                            <td id="getPrice" class="price">$. {{ $details['price'] }} </td>
                            <td id="itemTotal" class="price">$. {{ $details['price'] * $details['quantity'] }} </td>
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
            <div class="cart-summary">
                <div class="row">
                    <div class="col-7 text-right text-muted">Order total:</div>
                    <div class="col-5"><strong>$. {{$total }}</strong></div>
                </div>
                <hr class="hr-sm">
                <div class="row text-lg">
                    <div class="col-7 text-right text-muted">Total:</div>
                    <div class="col-5"><strong>$. {{$total}}</strong></div>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ url('/checkout') }}" class="panel-cart-action btn btn-secondary btn-block btn-lg"><span>Go to checkout</span></a>
</div>

<!-- Floating checkout button -->
<a href="{{url('/checkout')}}" class="float">
    <i class="fa fa-shopping-cart fa-4 my-float"></i>
</a>

<!-- Floating button text -->
<div class="label-container">
    <div class="label-text">Click here to checkout</div>
    <i class="fa fa-play label-arrow"></i>
</div>

@section('scripts')


    <script type="text/javascript">

        $("#update-cart").click(function (e) {
            e.preventDefault();

            var ele = $(this);

            $.ajax({
                url: '{{ url('update-cart') }}',
                method: "patch",
                data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), quantity: ele.parents("tr").find(".quantity").val()},
                success: function (response) {
                    window.location.reload();
                }
            });
        });

        $(".remove-from-cart").click(function (e) {
            e.preventDefault();

            var ele = $(this);

            if(confirm("Are you sure")) {
                $.ajax({
                    url: '{{ url('remove-from-cart') }}',
                    method: "DELETE",
                    data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id")},
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        });

    </script>

@endsection

<!-- JS Plugins -->
<script src="{{asset('plugins/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('plugins/tether/dist/js/tether.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('plugins/slick-carousel/slick/slick.min.js')}}"></script>
<script src="{{asset('plugins/jquery.appear/jquery.appear.js')}}"></script>
<script src="{{asset('plugins/jquery.scrollto/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('plugins/jquery.localscroll/jquery.localScroll.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery.mb.ytplayer/dist/jquery.mb.YTPlayer.min.js')}}"></script>
<script src="{{asset('plugins/twitter-fetcher/js/twitterFetcher_min.js')}}"></script>
<script src="{{asset('js/backend_js/matrix.form_validation.js') }}"></script>
<script src="{{asset('plugins/skrollr/dist/skrollr.min.js')}}"></script>
<script src="{{asset('plugins/animsition/dist/js/animsition.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha256-3blsJd4Hli/7wCQ+bmgXfOdK7p/ZUMtPXY08jmxSSgk=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('#country').on('change', function() {
            $('#mobile').val($(this).val());
        });
    })
</script>
<!-- JS Core -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAejxUruUkUhtoM5-PVb6ucheSwZVZuE-I&libraries=places,geometry"></script>
<script src="{{asset('js/core.js')}}"></script>
<script src="{{asset('test.js')}}"></script>
<script src="{{asset('js/delivery.js')}}"></script>
<script src="{{asset('js/payment.js')}}"></script>
<script src="{{asset('js/datatables.js')}}"></script>
</body>

</html>
