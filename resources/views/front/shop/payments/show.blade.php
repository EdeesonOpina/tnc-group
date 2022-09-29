@include('layouts.front.header')
@php
	use App\Models\PaymentStatus;
@endphp
		
	<main class="bg_gray">
		<div class="container margin_30">
		<div class="page_header">
			<div class="breadcrumbs">
				<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Orders</a></li>
					<li>My Orders</li>
				</ul>
			</div>
			<h1>My Orders</h1>
		</div>
		<!-- /page_header -->
		<table class="table table-striped cart-list">
				<thead>
					<tr>
						<th>Product</th>
						<th>Mode of Payment</th>
						<th>Status</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Subtotal</th>
						<th>
							
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($payments as $payment)
					<tr>
						<td>
							<div class="thumb_cart">
								<img src="{{ url('front/img/products/product_placeholder_square_small.jpg') }}" data-src="{{ url($payment->inventory->item->image ?? env('APP_ICON')) }}" class="lazy" alt="Image">
							</div>
							<span class="item_cart">{{ $payment->inventory->item->name }}</span>
						</td>
						<td>
							@if ($payment->mop == 'cod')
								<b>Cash On Delivery</b>
							@endif

							@if ($payment->mop == 'credit_card')
								<b>Credit Card</b>
							@endif

							@if ($payment->mop == 'bank_deposit')
								<b>Bank Deposit</b>
							@endif
						</td>
						<td>
							@if ($payment->status == PaymentStatus::PENDING)
								<span class="percentage" style="background: #f15726">Pending</span>
							@endif

							@if ($payment->status == PaymentStatus::CONFIRMED)
								<span class="percentage" style="background: #0C0">Confirmed</span>
							@endif

							@if ($payment->status == PaymentStatus::FOR_DELIVERY)
								<span class="percentage" style="background: #0C0">For Delivery</span>
							@endif

							@if ($payment->status == PaymentStatus::DELIVERED)
								<span class="percentage" style="background: #0C0">Delivered</span>
							@endif

							@if ($payment->status == PaymentStatus::CANCELLED)
								<span class="percentage">Cancelled</span>
							@endif
						</td>
						<td>
							<strong>₱{{ number_format($payment->price, 2) }}</strong>
						</td>
						
						<td>
							{{ $payment->qty }}
						</td>
						<td>
							<strong>₱{{ number_format($payment->total, 2) }}</strong>
						</td>
						<td class="options">
							<a href="{{ route('shop.carts.delete', [$payment->id]) }}"><i class="ti-trash"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>

			@if (count($payments) == 0)
				<div class="container text-center">
					<label>No record/s found</label>
				</div>
			@endif
	
		</div>
		<!-- /container -->

		@include('layouts.partials.owl-interesting-items')

		@include('layouts.partials.footer-features')
		
	</main>
	<!--/main-->
	
@include('layouts.front.footer')