@include('layouts.front.header')
		
<main>
	<div class="bg_gray">
		<div class="container margin_30">
			<div class="page_header">
				<div class="breadcrumbs">
					<ul>
						<li><a href="{{ route('site.index') }}">Home</a></li>
						<li><a href="{{ route('build') }}">Build</a></li>
						<li><a href="{{ route('build') }}">My Dream Rig</a></li>
						<li>Storage</li>
					</ul>
				</div>
				<h1>Storage</h1>
			</div>
			<!-- /page_header -->
		</div>
	</div>

	<div class="container margin_30">
		<div class="row">
			<div class="col-md-7">
				<form action="{{ route('build.storage.search') }}" method="post">
					{{ csrf_field() }}
				<div class="row">
					<div class="col-md-7">
						<h3>Storage</h3>
						<a href="{{ route('build.casing') }}"><i class="ti-angle-double-left"></i> Go back to Casing</a><br>
						<a href="{{ route('build.result') }}"><i class="ti-angle-double-right"></i> Finish</a>
					</div>
					<div class="col">
						<input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
					</div>
					<div class="col-md-1">
						<button type="submit" class="btn_1 cart" style="padding: 10px; width: 100%;"><i class="ti-search"></i></button>
					</div>
				</div>
				</form>
				<br>
				<table class="table table-striped cart-list">
					<thead>
						<tr>
							<th>Product</th>
							<th>Price</th>
							<th>
							</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($inventories as $inventory)
						<tr>
							<td>
								<div class="thumb_cart">
									<a href="{{ route('shop.items.view', [$inventory->id]) }}">
									<img src="{{ url('front/img/products/product_placeholder_square_small.jpg') }}" data-src="{{ url($inventory->item->image ?? 'front/img/products/product_placeholder_square_small.jpg') }}" class="lazy" alt="Image">
									</a>
								</div>
								<span class="item_cart">
									<a href="{{ route('shop.items.view', [$inventory->id]) }}" target="_blank" style="color: #212529">
										{{ $inventory->item->name }}
									</a>
								</span>
							</td>
							<td>
								<strong>₱{{ number_format($inventory->price, 2) }}</strong>
							</td>
							<td class="options">
								<a href="{{ route('build.storage.create', [$inventory->id]) }}"><i class="ti-shopping-cart"></i></a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				{{ $inventories->links() }}
			</div>

			<div class="col-md-5">
				<h3>My Dream Rig</h3><br>
				<table class="table table-striped cart-list">
					<thead>
						<tr>
							<th></th>
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
										<img src="{{ url('front/img/products/product_placeholder_square_small.jpg') }}" data-src="{{ url($build_item->inventory->item->image ?? 'front/img/products/product_placeholder_square_small.jpg') }}" class="lazy" alt="Image">
									</div>
									
								</td>
								<td><span class="item_cart">{{ $build_item->inventory->item->name }}</span></td>
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
					<a href="{{ route('build.result') }}" class="btn_1 full-width cart">Finish Building</a>
				</div>
				
			</div>
		</div>
	</div>


	@include('layouts.partials.footer-features')
	
</main>
<!--/main-->
	
@include('layouts.front.footer')