@extends('layouts.frontLayout.front_unique_design')
@section('content')
	<div id="content">
		<!-- Page Title -->
		<div class="page-title bg-dark dark">
			<!-- BG Image -->
			<div class="bg-image bg-parallax"><img src="assets/img/photos/bg-croissant.jpg" alt=""></div>
			<div class="container">
				<div class="row">
					<div class="col-lg-8 push-lg-4">
						<h1 class="mb-0">Order Details</h1>
						<h4 class="text-muted mb-0">View details of all the orders you have made</h4>
					</div>
				</div>
			</div>
		</div>

		<!-- Section -->
		<section class="section bg-light">
			<div class="container">
				<div class="panel-cart-container">
					<div class="row-fluid">
						<div class="span12">
							<div class="widget-box">
								<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
									<h5>Your Orders</h5>
								</div>
								<div class="widget-content nopadding">
									<table class="table table-bordered data-table table-responsive" id="myOrders">
										<thead>
											<tr>
												<th>Order Id</th>
												<th>Food Ordered</th>
												<th>Quantity</th>
												<th>Item Price</th>
												<th>Delivery Cost</th>
												<th>Paid Amount</th>
												<th>Method</th>
												<th>Status</th>
												<th>Total</th>
												<th>Date Ordered</th>
												{{-- <th>Actions</th> --}}
											</tr>
										</thead>
										@foreach($orderDetails as $order)
											<tbody>
												<tr class="gradeX">
													<td>{{$order->id}}</td>
													<td>
														@foreach($order->orderItems as $item)
															{{ '- ' . $item->product->product_name}} {{ $item->productAttribute ? $item->productAttribute->size ? ' (' . $item->productAttribute->size . ')' : '' : '' }}
															{{ $item->productAttribute ? $item->productAttribute->accompaniment ? ' + ' . $item->productAttribute->accompaniment : '' : '' }} <br/>
														@endforeach
													</td>
													<td>
														@foreach($order->orderItems as $item)
															{{$item->quantity}} <br/>
														@endforeach
													</td>
													<td>
														@foreach($order->orderItems as $item)
															{{ 'Ksh. ' . $item->price }} <br/>
														@endforeach
													</td>
													<td>Ksh. {{$order->deliveryDetails && count($order->deliveryDetails) > 0 ? $order->deliveryDetails[0] ? $order->deliveryDetails[0]['delivery_charge'] : 'N/A' : 'N/A'}}</td>
													<td>Ksh. {{$order->paymentDetails && count($order->paymentDetails) > 0  ? $order->paymentDetails[0] ? $order->paymentDetails[0]['payment_details_amount'] : 'N/A' : 'N/A'}}</td>
													<td>{{$order->paymentDetails && count($order->paymentDetails) > 0  ?  $order->paymentDetails[0] ? strtoupper(	$order->paymentDetails[0]['payment_details_type']) : 'N/A' : 'N/A'}}</td>
													<td>{{$order->paymentDetails && count($order->paymentDetails) > 0  ? $order->paymentDetails[0] ? $order->paymentDetails[0]['payment_details_status'] : 'N/A' : 'N/A'}}</td>
													<td>Ksh. {{$order->total}}</td>
													<td>{{date('jS l, F Y h:i A',strtotime($order->created_at	))}}</td>
													{{-- <td class="gradeX">
														<a href="#myModal{{$order->id}}" data-toggle="modal"
															class="btn btn-success btn-mini" title="View Product">View Order
															Details</a>
													</td> --}}
												</tr>
											</tbody>
											<div id="myModal{{$order->id}}" class="modal" ole="dialog">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h4 class="modal-title" id="myModalLabel">Your Order Details</h4>
															<button type="button" class="close" data-dismiss="modal"
																aria-label="Close"><i class="ti-close"></i></button>
														</div>
														<div class="modal-body">
															<p>Coming soon</p>
														</div>
													</div>
												</div>
											</div>
										@endforeach
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection
