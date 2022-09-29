@include('layouts.front.header')
		
	<main>
		<div class="top_banner">
			<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.8)">
				<div class="container">
					<div class="breadcrumbs">
						<ul>
							<li><a href="#">Home</a></li>
							<li>Shop</li>
						</ul>
					</div>
					<h1>Shop</h1>
				</div>
			</div>
			<img src="{{ url('front/img/sections/bg.jpg') }}" class="img-fluid" alt="">
		</div>
		<!-- /top_banner -->	

			<div class="container margin_30">
			<div class="row small-gutters">
				<div class="main_title text-left">
	                <h2>Shop Here With Our Items</h2>
	                <p>Find the best item for you.</p>
	            </div>
				@foreach($inventories as $inventory)
					<div class="col-6 col-md-4 col-xl-3">
						<div class="grid_item">
							<span class="ribbon new">New</span>
							<figure>
								<a href="{{ route('shop.items.view', [$inventory->id]) }}">
									<img class="img-fluid lazy" src="{{ url('front/img/products/product_placeholder_square_medium.jpg') }}" data-src="{{ url($inventory->item->image ?? env('APP_ICON')) }}" alt="" style="height: 200px;">
								</a>
								<!-- <div data-countdown="2019/05/10" class="countdown"></div> -->
							</figure>
							<a href="{{ route('shop.items.view', [$inventory->id]) }}">
								<h3>{{ $inventory->item->name }}</h3>
							</a>
							<div class="price_box">
								<span class="new_price">₱{{ number_format($inventory->price, 2) }}</span>
							</div>
							<ul>
								<li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a></li>
								<li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
								<li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
							</ul>
						</div>
						<!-- /grid_item -->
					</div>
					<!-- /col -->
				@endforeach
			</div>
			<!-- /row -->

			<div class="pagination__wrapper">
				<ul class="pagination">
					{{ $inventories->links() }}
				</ul>
			</div>
				
			<div class="container margin_30">
		    <div class="top_banner version_2">
		        <div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0)">
		            <div class="container">
		                <div class="d-flex justify-content-center">
		                    <h1>Top Quality Products</h1>
		                </div>
		            </div>
		        </div>
		        <img src="{{ url('front/img/banners/3.jpg') }}" class="img-fluid" alt="">
		    </div>
		    <!-- /top_banner -->

		    <div id="stick_here"></div>
	    	<div class="toolbox elemento_stick version_2"></div>

			@include('layouts.partials.row-interesting-items')
		</div>

		@include('layouts.partials.masonry-categories')
		</div>
		<!-- /container -->

		@include('layouts.partials.footer-features')
	</main>
	<!-- /main -->
	
@include('layouts.front.footer')