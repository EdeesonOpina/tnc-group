@include('layouts.front.header')
		
<main class="bg_gray">
	
		
	<div class="container margin_30">
		<div class="page_header">
			<div class="breadcrumbs">
				<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Orders</a></li>
					<li><a href="#">My Cart</a></li>
					<li>Checkout</li>
				</ul>
		</div>
		<h1>Shipping Details</h1>
			
	</div>
	<!-- /page_header -->
	<form action="{{ route('shop.carts.checkout.create') }}" method="post">
		{{ csrf_field() }}
	
		<div class="row">
			<div class="col-lg-4 col-md-6">
				<div class="step first">
				<h3>1. User Info and Billing address</h3>
				<div class="tab-content checkout">
					<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
						<div class="row no-gutters">
							<div class="col-6 form-group pr-1">
								<input type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ old('firstname') ?? auth()->user()->firstname }}">
							</div>
							<div class="col-6 form-group pl-1">
								<input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ old('lastname') ?? auth()->user()->lastname }}">
							</div>
						</div>
						<!-- /row -->
						<div class="form-group">
							<input type="text" name="line_address_1" class="form-control" placeholder="Line Address 1" value="{{ old('line_address_1') ?? auth()->user()->line_address_1 }}">
						</div>
						<div class="form-group">
							<input type="text" name="line_address_2" class="form-control" placeholder="Line Address 2" value="{{ old('line_address_2') ?? auth()->user()->line_address_2 }}">
						</div>
						<!-- <div class="row no-gutters">
							<div class="col-6 form-group pr-1">
								<input type="text" class="form-control" placeholder="City">
							</div>
							<div class="col-6 form-group pl-1">
								<input type="text" class="form-control" placeholder="Postal code" name="postal_code">
							</div>
						</div> -->
						<!-- /row -->
						<div class="row no-gutters">
							<div class="col-md-12 form-group">
								<div class="custom-select-form">
									<select class="wide add_bottom_15" name="country_id" id="country">
										<option value="1" selected>Philippines</option>
									</select>
								</div>
							</div>
						</div>
						<!-- /row -->
						<div class="row no-gutters">
							<div class="col-6 form-group pr-1">
								<input type="text" class="form-control" name="mobile" placeholder="Mobile No." value="{{ old('mobile') ?? auth()->user()->mobile }}">
							</div>
							<div class="col-6 form-group pl-1">
								<input type="text" class="form-control" name="phone" placeholder="Phone No." value="{{ old('phone') ?? auth()->user()->phone }}">
							</div>
						</div>
					</div>
					<!-- /tab_1 -->
				</div>
				</div>
				<!-- /step -->
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="step middle payments">
					<h3>2. Payment and Shipping</h3>
						<ul>
							<li>
								<label class="container_radio">Cash on delivery<a href="#0" class="info" data-bs-toggle="modal" data-bs-target="#payments_method"></a>
									<input type="radio" name="mop" value="cod" checked>
									<span class="checkmark"></span>
								</label>
							</li>
							<li>
								<label class="container_radio">Credit Card<a href="#0" class="info" data-bs-toggle="modal" data-bs-target="#payments_method"></a>
									<input type="radio" name="mop" value="credit_card" disabled>
									<span class="checkmark"></span>
								</label>
							</li>
							<!-- <li>
								<label class="container_radio">Paypal<a href="#0" class="info" data-bs-toggle="modal" data-bs-target="#payments_method"></a>
									<input type="radio" name="payment">
									<span class="checkmark"></span>
								</label>
							</li> -->
							<li>
								<label class="container_radio">Bank Transfer<a href="#0" class="info" data-bs-toggle="modal" data-bs-target="#payments_method"></a>
									<input type="radio" name="mop" value="bank_transfer" disabled>
									<span class="checkmark"></span>
								</label>
							</li>
						</ul>
						<h6 class="pb-2">Shipping Method</h6>
						
					
					<ul>
							<li>
								<label class="container_radio">Standard shipping<a href="#0" class="info" data-bs-toggle="modal" data-bs-target="#payments_method"></a>
									<input type="radio" name="shipping" checked>
									<span class="checkmark"></span>
								</label>
							</li>
							<!-- <li>
								<label class="container_radio">Express shipping<a href="#0" class="info" data-bs-toggle="modal" data-bs-target="#payments_method"></a>
									<input type="radio" name="shipping">
									<span class="checkmark"></span>
								</label>
							</li> -->
							
						</ul>
					
				</div>
				<!-- /step -->
				
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="step last">
					<h3>3. Order Summary</h3>
				<div class="box_general summary">
					<ul>
						@foreach ($carts as $cart)
							<li class="clearfix"><em>{{ $cart->qty }}x {{ $cart->inventory->item->name }}</em>  <span>₱{{ number_format($cart->qty * $cart->price, 2) }}</span></li>
						@endforeach
					</ul>
					<ul>
						<li class="clearfix"><em><strong>Subtotal</strong></em>  <span>₱{{ number_format($carts_total, 2) }}</span></li>
						<li class="clearfix"><em><strong>Shipping</strong></em> <span>₱0.00</span></li>
						
					</ul>
					<div class="total clearfix">TOTAL <span>₱{{ number_format($carts_total, 2) }}</span></div>
					<div class="form-group">
							<label class="container_check">Register to the Newsletter.
							  <input type="checkbox" checked>
							  <span class="checkmark"></span>
							</label>
						</div>
					
					<button type="submit" class="btn_1 full-width">Confirm and Pay</button>
				</div>
				<!-- /box_general -->
				</div>
				<!-- /step -->
			</div>
		</div>
		<!-- /row -->

	</form>
	</div>
	<!-- /container -->
</main>
<!--/main-->
	
@include('layouts.front.footer')