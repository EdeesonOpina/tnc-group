@include('layouts.front.header')
		
	<main>
	    <div class="container margin_30">
	        <div class="row">
	            <div class="col-md-6">
	                <div class="all">
	                    <div class="slider">
	                        <div class="owl-carousel owl-theme main">
	                            <div style="background-image: url({{ url($inventory->item->image ?? env('APP_ICON')) }});" class="item-box"></div>
	                            @foreach($item_photos as $item_photo)
	                            	<div style="background-image: url({{ url($item_photo->image) }});" class="item-box"></div>
	                            @endforeach
	                        </div>
	                        <div class="left nonl"><i class="ti-angle-left"></i></div>
	                        <div class="right"><i class="ti-angle-right"></i></div>
	                    </div>
	                    <div class="slider-two">
	                        <div class="owl-carousel owl-theme thumbs">
	                            <div style="background-image: url({{ url($inventory->item->image ?? env('APP_ICON')) }});" class="item active"></div>
	                            @foreach($item_photos as $item_photo)
	                            	<div style="background-image: url({{ url($item_photo->image) }});" class="item"></div>
	                            @endforeach
	                        </div>
	                        <div class="left-t nonl-t"></div>
	                        <div class="right-t"></div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-6">
	                <div class="breadcrumbs">
	                    <ul>
	                        <li><a href="#">Shop</a></li>
	                        <li><a href="#">{{ $inventory->item->category->name }}</a></li>
	                        <li>{{ $inventory->item->name }}</li>
	                    </ul>
	                </div>
	                <!-- /page_header -->
	                <div class="prod_info">
	                    <h1>{{ $inventory->item->name }}</h1>
	                    <span class="rating"><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star voted"></i><i class="icon-star"></i><em>4 reviews</em></span>
	                    <p><small>BRAND: {{ $inventory->item->brand->name }}</small><br>
	                    <small>CATEGORY: {{ $inventory->item->category->name }}</small><br>
	                    </p>
	                    <form action="{{ route('shop.carts.create') }}" method="post">
	                    {{ csrf_field() }}
	                    <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
	                    <div class="prod_options">
	                        <div class="row">
	                            <label class="col-xl-5 col-lg-5  col-md-6 col-6"><strong>Quantity</strong></label>
	                            <div class="col-xl-4 col-lg-5 col-md-6 col-6">
	                                <div class="numbers-row">
	                                    <input type="text" value="1" id="quantity_1" class="qty2" name="qty">
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-lg-5 col-md-6">
	                            <div class="price_main"><span class="new_price">₱{{ number_format($inventory->price, 2) }}</span></div>
	                        </div>
	                        <div class="col-lg-4 col-md-6">
	                            <div class="btn_add_to_cart"><button type="submit" class="btn_1">Add to Cart</button></div>
	                        </div>
	                    </div>
	                    </form>
	                </div>
	                <!-- /prod_info -->
	                <div class="product_actions">
	                    <ul>
	                        <li>
	                            <a href="#"><i class="ti-heart"></i><span>Add to Wishlist</span></a>
	                        </li>
	                        <li>
	                            <a href="#"><i class="ti-control-shuffle"></i><span>Add to Compare</span></a>
	                        </li>
	                    </ul>
	                </div>
	                <!-- /product_actions -->
	            </div>
	        </div>
	        <!-- /row -->
	    </div>
	    <!-- /container -->
	    
	    <div class="tabs_product">
	        <div class="container">
	            <ul class="nav nav-tabs" role="tablist">
	                <li class="nav-item">
	                    <a id="tab-A" href="#pane-A" class="nav-link active" data-bs-toggle="tab" role="tab">Description</a>
	                </li>
	                <li class="nav-item">
	                    <a id="tab-B" href="#pane-B" class="nav-link" data-bs-toggle="tab" role="tab">Reviews</a>
	                </li>
	            </ul>
	        </div>
	    </div>
	    <!-- /tabs_product -->
	    <div class="tab_content_wrapper">
	        <div class="container">
	            <div class="tab-content" role="tablist">
	                <div id="pane-A" class="card tab-pane fade active show" role="tabpanel" aria-labelledby="tab-A">
	                    <div class="card-header" role="tab" id="heading-A">
	                        <h5 class="mb-0">
	                            <a class="collapsed" data-bs-toggle="collapse" href="#collapse-A" aria-expanded="false" aria-controls="collapse-A">
	                                Description
	                            </a>
	                        </h5>
	                    </div>
	                    <div id="collapse-A" class="collapse" role="tabpanel" aria-labelledby="heading-A">
	                        <div class="card-body">
	                            <div class="row justify-content-between">
	                                <div class="col-lg-12">
	                                    <h3>Details</h3>
	                                    {!! $inventory->item->description !!}
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <!-- /TAB A -->
	                <div id="pane-B" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
	                    <div class="card-header" role="tab" id="heading-B">
	                        <h5 class="mb-0">
	                            <a class="collapsed" data-bs-toggle="collapse" href="#collapse-B" aria-expanded="false" aria-controls="collapse-B">
	                                Reviews
	                            </a>
	                        </h5>
	                    </div>
	                    <div id="collapse-B" class="collapse" role="tabpanel" aria-labelledby="heading-B">
	                        <div class="card-body">
	                        	
	                            <div class="row justify-content-between">
	                            	@foreach ($reviews as $review)
	                                <div class="col-lg-6">
	                                    <div class="review_content">
	                                        <div class="clearfix add_bottom_10">
	                                            <span class="rating"><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><em>{{ $review->value }}/5.0</em></span>
	                                            <em>Published {{ $review->created_at->diffForHumans() }}</em>
	                                        </div>
	                                        <h4>"{{ $review->title }}"</h4>
	                                        <p>{!! $review->description !!}</p>
	                                    </div>
	                                </div>
	                                @endforeach
	                                
	                                @if (count($reviews) == 0)
	                                	<div class="col-lg-6">
	                                		<div class="review_content">
	                                			<h3>No reviews yet</h3>
	                                		</div>
	                                	</div>
	                                @endif
	                            </div>
	                            
	                            <!-- /row -->
	                            <p class="text-end"><a href="{{ route('shop.items.reviews.add', [$inventory->id]) }}" class="btn_1">Leave a review</a></p>
	                        </div>
	                        <!-- /card-body -->
	                    </div>
	                </div>
	                <!-- /tab B -->
	            </div>
	            <!-- /tab-content -->
	        </div>
	        <!-- /container -->
	    </div>
	    <!-- /tab_content_wrapper -->

	    @include('layouts.partials.owl-interesting-items')

	    @include('layouts.partials.footer-features')

	</main>
	<!-- /main -->
	
@include('layouts.front.footer')