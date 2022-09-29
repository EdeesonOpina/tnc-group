@include('layouts.front.header')
		
<main class="bg_gray">
		<div id="track_order">
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="col-xl-7 col-lg-9">
						<img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" alt="" class="img-fluid add_bottom_15" width="200" height="177">
						<p>Tracking Order</p>
						<form>
							<div class="search_bar">
								<input type="text" class="form-control" placeholder="Invoice ID">
								<input type="submit" value="Search">
							</div>
						</form>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /track_order -->
		
		<div class="bg_white">
		<div class="container margin_60_35">
	        <div class="main_title">
	            <h2>Items You Might Be Interested</h2>
	            <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
	        </div>
	        <div class="owl-carousel owl-theme products_carousel">
	            @foreach($interesting_items as $interesting_item)
	            <div class="item">
	                <div class="grid_item">
	                    <span class="ribbon new">New</span>
	                    <figure>
	                        <a href="{{ route('shop.items.view', [$interesting_item->id]) }}">
	                            <img class="owl-lazy" src="{{ url('front/img/products/product_placeholder_square_medium.jpg') }}" data-src="{{ url($interesting_item->item->image ?? env('APP_ICON')) }}" alt="" style="height: 250px">
	                        </a>
	                    </figure>
	                    <div class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i></div>
	                    <a href="{{ route('shop.items.view', [$interesting_item->id]) }}">
	                        <h3>{{ $interesting_item->item->name }}</h3>
	                    </a>
	                    <div class="price_box">
	                        <span class="new_price">₱{{ number_format($interesting_item->price, 2) }}</span>
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
		<!-- /container -->
		</div>
		<!-- /bg_white -->
	</main>
	<!--/main-->
	
@include('layouts.front.footer')