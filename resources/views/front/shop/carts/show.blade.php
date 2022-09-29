@include('layouts.front.header')
		
<main class="bg_gray">
	<div class="container margin_30">
	<div class="page_header">
		<div class="breadcrumbs">
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">Orders</a></li>
				<li>My Cart</li>
			</ul>
		</div>
		<h1>My Cart</h1>
	</div>
	<!-- /page_header -->
	<table class="table table-striped cart-list">
			<thead>
				<tr>
					<th>Product</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>
						
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($carts as $cart)
				<tr>
					<td>
						<div class="thumb_cart">
							<img src="{{ url('front/img/products/product_placeholder_square_small.jpg') }}" data-src="{{ url($cart->inventory->item->image ?? env('APP_ICON')) }}" class="lazy" alt="Image">
						</div>
						<span class="item_cart">{{ $cart->inventory->item->name }}</span>
					</td>
					<td>
						<strong>₱{{ number_format($cart->price, 2) }}</strong>
					</td>
					<td>
						<strong>{{ $cart->qty }}</strong>
					</td>
					<td>
						<strong>₱{{ number_format($cart->total, 2) }}</strong>
					</td>
					<td class="options">
						<a href="{{ route('shop.carts.delete', [$cart->id]) }}"><i class="ti-trash"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<div class="row add_top_30 flex-sm-row-reverse cart_actions">
		<div class="col-sm-4 text-end">
			<!-- <button type="button" class="btn_1 gray">Update Cart</button> -->
		</div>
		<div class="col-sm-8">
			<div class="apply-coupon">
				<div class="form-group">
					<div class="row g-2">
						<div class="col-md-6"><input type="text" name="coupon-code" value="" placeholder="Promo code" class="form-control"></div>
						<div class="col-md-4"><button type="button" class="btn_1 outline">Apply Coupon</button></div>
					</div>
				</div>
			</div>
		</div>

		@if (count($carts) == 0)
			<div class="container text-center">
				<label>No record/s found</label>
			</div>
		@endif
	</div>
	<!-- /cart_actions -->

	</div>
	<!-- /container -->
	
	<div class="box_cart">
		<div class="container">
		<div class="row justify-content-end">
			<div class="col-xl-4 col-lg-4 col-md-6">
		<ul>
			<li>
				<span>Subtotal</span> ₱{{ number_format($carts_total, 2) }}
			</li>
			<li>
				<span>Shipping</span> ₱0.00
			</li>
			<li>
				<span>Total</span> ₱{{ number_format($carts_total, 2) }}
			</li>
		</ul>
		<a href="{{ route('shop.carts.checkout') }}" class="btn_1 full-width cart">Proceed to Checkout</a>
				</div>
			</div>
		</div>
	</div>
	<!-- /box_cart -->

	@include('layouts.partials.owl-interesting-items')

	@include('layouts.partials.footer-features')
	
</main>
<!--/main-->
	
@include('layouts.front.footer')