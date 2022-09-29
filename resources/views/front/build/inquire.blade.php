@include('layouts.front.header')
@php
	use App\Models\Category;
@endphp
<main>
	<div class="bg_gray">
		<div class="container margin_30">
			<div class="page_header">
				<div class="breadcrumbs">
					<ul>
						<li><a href="{{ route('site.index') }}">Home</a></li>
						<li><a href="{{ route('build') }}">Build</a></li>
						<li><a href="{{ route('build') }}">My Dream Rig</a></li>
						<li>PC Build Summary</li>
					</ul>
				</div>
				<h1>PC Build Summary</h1>
			</div>
			<!-- /page_header -->
		</div>
	</div>

	<div class="container margin_30">
		<div class="row">
			<div class="col-md-7">
				<h3>My Dream Rig</h3><br>
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
						@foreach ($build_items as $build_item)
							<tr>
								<td>
								<div class="thumb_cart">
									<a href="{{ route('shop.items.view', [$build_item->inventory->id]) }}">
									<img src="{{ url('front/img/products/product_placeholder_square_small.jpg') }}" data-src="{{ url($build_item->inventory->item->image ?? 'front/img/products/product_placeholder_square_small.jpg') }}" class="lazy" alt="Image">
									</a>
								</div>
									<span class="item_cart">
										<a href="{{ route('shop.items.view', [$build_item->inventory->id]) }}" target="_blank" style="color: #212529">
											{{ $build_item->inventory->item->name }}
										</a>
									</span>
								</td>
								<td>
									<strong>₱{{ number_format($build_item->inventory->price, 2) }}</strong>
								</td>
								<td>
									<strong>{{ $build_item->qty }}</strong>
								</td>
								<td>
									<strong>₱{{ number_format($build_item->total, 2) }}</strong>
								</td>
								<td class="options">
									<a href="{{ route('build.items.delete', [$build_item->id]) }}"><i class="ti-trash"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				@if (count($build_items) == 0)
					<div class="container text-center">
						<label>No record/s found</label>
					</div>
				@endif

				<div class="box_cart">
					<ul>
						<li>
							<span>Subtotal</span> ₱{{ number_format($build_items_total, 2) }}
						</li>
						<li>
							<span>Shipping</span> ₱0.00
						</li>
						<li>
							<span>Total</span> ₱{{ number_format($build_items_total, 2) }}
						</li>
					</ul>
				</div>
				
			</div>
			<div class="col-md-5">
				<h3>Inquiry</h3>
				<form action="{{ route('mail.build.inquire') }}" method="post">
  					{{ csrf_field() }}
					<div class="tab-content checkout">
						<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
							<div class="row no-gutters">
								<div class="col-6 form-group pr-1">
									<input type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ auth()->user()->firstname ?? null }}">
								</div>
								<div class="col-6 form-group pl-1">
									<input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ auth()->user()->lastname ?? null }}">
								</div>
							</div>
							<!-- /row -->
							<div class="row no-gutters">
								<div class="col-6 form-group pr-1">
									<input type="text" class="form-control" name="phone" placeholder="Phone No. (Optional)" value="{{ auth()->user()->phone ?? null }}">
								</div>
								<div class="col-6 form-group pl-1">
									<input type="text" class="form-control" name="mobile" placeholder="Mobile No." value="{{ auth()->user()->mobile ?? null }}">
								</div>
							</div>
							<div class="row no-gutters">
								<div class="col form-group pr-1">
									<input type="email" class="form-control" name="email" placeholder="Mobile No." value="{{ auth()->user()->email ?? null }}">
								</div>
							</div>
							<div class="form-group">
								<textarea class="form-control" name="description" style="height: 150px;" placeholder="Other remarks or a message to send to our team *"></textarea>
							</div>
							<div class="form-group">
								<input class="btn_1 full-width" type="submit" value="Submit">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	@include('layouts.partials.footer-features')
	
</main>
<!--/main-->
	
@include('layouts.front.footer')