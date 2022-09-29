@php
	use App\Models\Category;
	use App\Models\SubCategory;
@endphp

<div class="container margin_60_35">
	<div class="row small-gutters categories_grid">
		<div class="col-sm-12 col-md-6" style="background: #0F0F0F;">
			<a href="{{ route('shop.filter', ['*', '*', Category::APPAREL, '*']) }}">
				<br><br>
				<img src="img/img_cat_home_1_placeholder.png" data-src="{{ url('front/img/sections/apparel.png') }}" alt="" class="img-fluid lazy">
				<div class="wrapper">
					<h2>TNC Apparel</h2>
					<p>115 Products</p>
				</div>
			</a>
		</div>
		<div class="col-sm-12 col-md-6">
			<div class="row small-gutters mt-md-0 mt-sm-2">
				<div class="col-sm-6">
					<a href="{{ route('shop.filter', ['*', '*', Category::PERIPHERALS, '*']) }}">
						<img src="img/img_cat_home_2_placeholder.png" data-src="{{ url('front/img/banners/2.jpg') }}" alt="" class="img-fluid lazy">
						<div class="wrapper">
							<h2>Peripherals</h2>
							<p>150 Products</p>
						</div>
					</a>
				</div>
				<div class="col-sm-6">
					<a href="{{ route('shop.filter', ['*', '*', Category::PERIPHERALS, SubCategory::MONITOR]) }}">
						<img src="img/img_cat_home_2_placeholder.png" data-src="{{ url('front/img/banners/4.jpg') }}" alt="" class="img-fluid lazy">
						<div class="wrapper">
							<h2>Monitors</h2>
							<p>90 Products</p>
						</div>
					</a>
				</div>
				<div class="col-sm-12 mt-sm-2">
					<a href="{{ route('shop.filter', ['*', '*', Category::COMPONENTS, SubCategory::GRAPHICS_CARD]) }}">
						<img src="img/img_cat_home_4_placeholder.png" data-src="{{ url('front/img/banners/1.jpg') }}" alt="" class="img-fluid lazy">
						<div class="wrapper">
							<h2>Graphics Cards</h2>
							<p>120 Products</p>
						</div>
					</a>
				</div>
			</div>
		</div>

	</div>
	<!--/categories_grid-->
</div>
<!-- /container -->