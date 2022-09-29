@include('layouts.front.header')
@php
	use App\Models\Category;
	use App\Models\SubCategory;
@endphp
		
	<main>
		<div id="carousel-home">
			<div class="owl-carousel owl-theme">
				<div class="owl-slide cover" style="background-image: url({{ url('front/img/slides/featured-1.png') }});">
					<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.5)">
						<div class="container">
							<div class="row justify-content-center justify-content-md-end">
								<div class="col-lg-6 static">
									<div class="slide-text text-end white">
										<h2 class="owl-slide-animated owl-slide-title">Product Feature<br>Helios 300</h2>
										<p class="owl-slide-animated owl-slide-subtitle">
											*PH315-53-743Q
											Intel® Core™ i7-10750H (6 Cores/12 Threads)
											15.6" FHD IPS 240hz NTSC sRGB 100%
											16GB DDR4 2933mhz (with 1 open slot)
											512 GB NVMe SSD + 1TB 2.5" 7200 RPM
											NVIDIA® GeForce® RTX2060 6GB GDDR6
											4-zone RGB Backlit Keyboard
											Windows 10
										</p>
										<div class="owl-slide-animated owl-slide-cta"><a class="btn_1" href="{{ route('shop.filter', ['*', '*', '*', '*']) }}" role="button">Shop Now</a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/owl-slide-->
				<div class="owl-slide cover" style="background-image: url({{ url('front/img/slides/2.jpg') }});">
					<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.5)">
						<div class="container">
							<div class="row justify-content-center justify-content-md-start">
								<div class="col-lg-6 static">
									<div class="slide-text white">
										<h2 class="owl-slide-animated owl-slide-title">Attack Air<br>VaporMax Flyknit 3</h2>
										<p class="owl-slide-animated owl-slide-subtitle">
											Limited items available at this price
										</p>
										<div class="owl-slide-animated owl-slide-cta"><a class="btn_1" href="{{ route('shop.filter', ['*', '*', '*', '*']) }}" role="button">Shop Now</a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/owl-slide-->
				<div class="owl-slide cover" style="background-image: url({{ url('front/img/slides/3.jpg') }});">
					<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.5)">
						<div class="container">
							<div class="row justify-content-center justify-content-md-end">
								<div class="col-lg-6 static">
									<div class="slide-text text-end white">
										<h2 class="owl-slide-animated owl-slide-title">Attack Air<br>Max 720 Sage Low</h2>
										<p class="owl-slide-animated owl-slide-subtitle">
											Limited items available at this price
										</p>
										<div class="owl-slide-animated owl-slide-cta"><a class="btn_1" href="{{ route('shop.filter', ['*', '*', '*', '*']) }}" role="button">Shop Now</a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="icon_drag_mobile"></div>
		</div>
		<!--/carousel-->

		<ul id="banners_grid" class="clearfix">
			<li>
				<a href="#0" class="img_container">
					<img src="{{ url('front/img/banners_cat_placeholder.jpg') }}" data-src="{{ url('front/img/banners/1.jpg') }}" alt="" class="lazy">
					<div class="short_info opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
						<h3>Our Best Prices</h3>
						<div><span class="btn_1">Shop Now</span></div>
					</div>
				</a>
			</li>
			<li>
				<a href="#0" class="img_container">
					<img src="{{ url('front/img/banners_cat_placeholder.jpg') }}" data-src="{{ url('front/img/banners/2.jpg') }}" alt="" class="lazy">
					<div class="short_info opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
						<h3>Top Selling Items</h3>
						<div><span class="btn_1">Shop Now</span></div>
					</div>
				</a>
			</li>
			<li>
				<a href="#0" class="img_container">
					<img src="{{ url('front/img/banners_cat_placeholder.jpg') }}" data-src="{{ url('front/img/banners/3.jpg') }}" alt="" class="lazy">
					<div class="short_info opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
						<h3>Find The Best For You</h3>
						<div><span class="btn_1">Shop Now</span></div>
					</div>
				</a>
			</li>
		</ul>
		<!--/banners_grid -->
		
		<div class="container margin_60_35">
			<div class="main_title">
				<h2>Top Selling</h2>
				<span>Products</span>
				<p>Check out our top selling products</p>
			</div>
			<div class="row small-gutters">
				@foreach($inventories as $inventory)
				<div class="col-6 col-md-4 col-xl-3">
					<div class="grid_item">
						<span class="ribbon hot">Hot</span>
						<figure>
							<a href="{{ route('shop.items.view', [$inventory->id]) }}">
								<img class="img-fluid lazy" src="{{ url('front/img/products/product_placeholder_square_medium.jpg') }}" data-src="{{ url($inventory->item->image ?? 'front/img/products/product_placeholder_square_small.jpg') }}" alt="" style="height: 200px;">
							</a>
						</figure>
						<div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i></div>
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
		</div>
		<!-- /container -->

		<div class="featured lazy" style="background: url('{{ url('front/img/sections/bg-black.jpg') }}')">
			<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.0)">
				<div class="container margin_60">
					<div class="row justify-content-center justify-content-md-start">
						<div class="col-lg-6 wow" data-wow-offset="150">
							<h3>Official Merch Store<br>Phoenix Apparel</h3>
							<p>Wear The Colors Proud</p>
							<div class="feat_text_block">
								<a class="btn_1" href="{{ route('shop') }}" role="button" style="background: #FFC107; color: #111;">Shop Now</a>
							</div>
						</div>
						<div class="col-lg-6 wow" data-wow-offset="150">
							<img src="{{ url('front/img/sections/apparel.png') }}" width="350px">
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /featured -->

		<div class="container margin_60_35">
			<div class="main_title">
				<h2>Featured</h2>
				<span>Products</span>
				<p>Check out the items that you might be interested in</p>
			</div>
			<div class="owl-carousel owl-theme products_carousel">
				@foreach($most_viewed_inventories as $most_viewed_inventory)
				<div class="item">
					<div class="grid_item">
						<span class="ribbon new">New</span>
						<figure>
							<a href="{{ route('shop.items.view', [$most_viewed_inventory->id]) }}">
								<img class="owl-lazy" src="{{ url('front/img/products/product_placeholder_square_medium.jpg') }}" data-src="{{ url($most_viewed_inventory->item->image ?? env('APP_ICON')) }}" alt="" style="height: 250px;">
							</a>
						</figure>
						<div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i></div>
						<a href="{{ route('shop.items.view', [$most_viewed_inventory->id]) }}">
							<h3>{{ $most_viewed_inventory->item->name }}</h3>
						</a>
						<div class="price_box">
							<span class="new_price">₱{{ number_format($most_viewed_inventory->price, 2) }}</span>
						</div>
						<ul>
							<li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a></li>
							<li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
							<li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
						</ul>
					</div>
					<!-- /grid_item -->
				</div>
				<!-- /item -->
				@endforeach
			</div>
			<!-- /products_carousel -->
		</div>
		<!-- /container -->
		
		<div class="bg_gray">
			<div class="container margin_30">
				<div id="brands" class="owl-carousel owl-theme">
					@foreach($slider_brands as $slider_brand)
						<div class="item">
							<a href="{{ route('shop.filter', [$slider_brand->name, '*', '*', '*']) }}"><img src="{{ url('front/img/brands/placeholder_brands.png') }}" data-src="{{ url($slider_brand->image ?? env('APP_ICON')) }}" alt="" class="owl-lazy" style="padding: 15%;"></a>
						</div><!-- /item -->
					@endforeach
				</div><!-- /carousel -->
			</div><!-- /container -->
		</div>
		<!-- /bg_gray -->

		@include('layouts.partials.masonry-categories')

		<!-- <div class="container margin_60_35">
			<div class="main_title">
				<h2>Latest News</h2>
				<span>Blog</span>
				<p>Cum doctus civibus efficiantur in imperdiet deterruisset</p>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<a class="box_news" href="blog.html">
						<figure>
							<img src="{{ url('front/img/blog-thumb-placeholder.jpg') }}" data-src="{{ url('front/img/blog-thumb-1.jpg') }}" alt="" width="400" height="266" class="lazy">
							<figcaption><strong>28</strong>Dec</figcaption>
						</figure>
						<ul>
							<li>by Mark Twain</li>
							<li>20.11.2017</li>
						</ul>
						<h4>Pri oportere scribentur eu</h4>
						<p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum vidisse....</p>
					</a>
				</div>
				
				<div class="col-lg-6">
					<a class="box_news" href="blog.html">
						<figure>
							<img src="{{ url('front/img/blog-thumb-placeholder.jpg') }}" data-src="{{ url('front/img/blog-thumb-2.jpg') }}" alt="" width="400" height="266" class="lazy">
							<figcaption><strong>28</strong>Dec</figcaption>
						</figure>
						<ul>
							<li>By Jhon Doe</li>
							<li>20.11.2017</li>
						</ul>
						<h4>Duo eius postea suscipit ad</h4>
						<p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum vidisse....</p>
					</a>
				</div>
				
				<div class="col-lg-6">
					<a class="box_news" href="blog.html">
						<figure>
							<img src="{{ url('front/img/blog-thumb-placeholder.jpg') }}" data-src="{{ url('front/img/blog-thumb-3.jpg') }}" alt="" width="400" height="266" class="lazy">
							<figcaption><strong>28</strong>Dec</figcaption>
						</figure>
						<ul>
							<li>By Luca Robinson</li>
							<li>20.11.2017</li>
						</ul>
						<h4>Elitr mandamus cu has</h4>
						<p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum vidisse....</p>
					</a>
				</div>
				
				<div class="col-lg-6">
					<a class="box_news" href="blog.html">
						<figure>
							<img src="{{ url('front/img/blog-thumb-placeholder.jpg') }}" data-src="{{ url('front/img/blog-thumb-4.jpg') }}" alt="" width="400" height="266" class="lazy">
							<figcaption><strong>28</strong>Dec</figcaption>
						</figure>
						<ul>
							<li>By Paula Rodrigez</li>
							<li>20.11.2017</li>
						</ul>
						<h4>Id est adhuc ignota delenit</h4>
						<p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum vidisse....</p>
					</a>
				</div>
			</div>
		</div> -->
		
	</main>
	<!-- /main -->
	
@include('layouts.front.footer')