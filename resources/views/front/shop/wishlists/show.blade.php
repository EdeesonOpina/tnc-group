@include('layouts.front.header')
		
	<main class="bg_gray">
		<div class="container margin_30">
		<div class="page_header">
			<div class="breadcrumbs">
				<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Wishlists</a></li>
					<li>My Wishlists</li>
				</ul>
			</div>
			<h1>My Wishlists</h1>
		</div>
		<!-- /page_header -->
		<table class="table table-striped cart-list">
				<thead>
					<tr>
						<th>Product</th>
						<th>Price</th>
						<th>Subtotal</th>
						<th>
							
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($wishlists as $wishlist)
					<tr>
						<td>
							<div class="thumb_cart">
								<img src="{{ url('front/img/products/product_placeholder_square_small.jpg') }}" data-src="{{ url($wishlist->item->image ?? env('APP_ICON')) }}" class="lazy" alt="Image">
							</div>
							<span class="item_cart">{{ $wishlist->item->name }}</span>
						</td>
						<td>
							<strong>₱{{ number_format($wishlist->price, 2) }}</strong>
						</td>
						<td>
							<strong>₱{{ number_format($wishlist->total, 2) }}</strong>
						</td>
						<td class="options">
							<a href="{{ route('shop.wishlists.delete', [$wishlist->id]) }}"><i class="ti-trash"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>

			@if (count($wishlists) == 0)
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