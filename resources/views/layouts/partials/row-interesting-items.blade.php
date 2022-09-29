@php
	use App\Models\Inventory;
	use App\Models\InventoryStatus;

	$interesting_items = Inventory::where('status', InventoryStatus::ACTIVE)
							->where('price', '>', 0)
                            ->orderByRaw('rand()')
                            ->get()
                            ->take(8);
@endphp
<div class="row small-gutters">
	<div class="main_title">
        <h2>Items You Might Be Interested</h2>
        <p>Check out the items that you might be interested in</p>
    </div>
	@foreach($interesting_items as $interesting_item)
		<div class="col-6 col-md-4 col-xl-3">
			<div class="grid_item">
				<span class="ribbon new">New</span>
				<figure>
					<a href="{{ route('shop.items.view', [$interesting_item->id]) }}">
						<img class="img-fluid lazy" src="{{ url('front/img/products/product_placeholder_square_medium.jpg') }}" data-src="{{ url($interesting_item->item->image ?? env('APP_ICON')) }}" alt="" style="height: 200px;">
					</a>
					<!-- <div data-countdown="2019/05/10" class="countdown"></div> -->
				</figure>
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
		<!-- /col -->
	@endforeach			
</div>
<!-- /row -->