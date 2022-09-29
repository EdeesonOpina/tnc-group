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
			<div class="col-md-8">
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
					<div class="row">
						<div class="col">
							<!-- <a href="{{ route('build.carts.create') }}" class="btn_1 full-width cart"><i class="ti-shopping-cart"></i> Add To Cart</a> -->
							<form action="{{ route('build.carts.create') }}" method="post">
								{{ csrf_field() }}
								<button class="btn_1 full-width cart"><i class="ti-shopping-cart"></i> Add To Cart</button>
							</form>
						</div>
						<div class="col">
							<a href="{{ route('build.inquire') }}" class="btn_1 full-width cart"><i class="ti-email"></i> Inquire</a>
						</div>
					</div>
					
				</div>
				
			</div>
			<div class="col-md-4">
				<h3>Official Merch Store</h3><br>
				<a href="{{ route('shop.filter', ['*', '*', Category::APPAREL, '*']) }}">
				<img src="{{ url('front/img/sections/apparel.png') }}" data-src="{{ url('front/img/sections/apparel.png') }}" width="100%" height="550" alt="" class="img-fluid lazy">
				</a>
			</div>
		</div>
	</div>

	@include('layouts.partials.footer-features')
	
</main>
<!--/main-->
	
@include('layouts.front.footer')