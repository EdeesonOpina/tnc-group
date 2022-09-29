@php
	use App\Models\Cart;
	use App\Models\Inventory;
	use App\Models\InventoryStatus;
	use App\Models\Brand;
	use App\Models\BrandStatus;
	use App\Models\Category;
	use App\Models\CartStatus;
	use App\Models\CategoryStatus;
	use App\Models\SubCategory;
	use App\Models\SubCategoryStatus;

	$brands = Brand::where('status', BrandStatus::ACTIVE)
				->orderBy('name', 'asc')
				->get()
				->take(14);
	$full_categories = Category::where('status', CategoryStatus::ACTIVE)
				->orderBy('name', 'asc')
				->get();
	$categories = Category::where('status', CategoryStatus::ACTIVE)
				->orderBy('name', 'asc')
				->get()
				->take(6);
	$components = SubCategory::where('status', SubCategoryStatus::ACTIVE)
				->where('category_id', Category::COMPONENTS)
				->orderBy('name', 'asc')
				->get();

	$inventories_count = Inventory::where('status', InventoryStatus::ACTIVE)
						->count();

	if (auth()->check()) {
		$carts = Cart::with(['inventory'])
					->where('status', CartStatus::ACTIVE)
					->where('user_id', auth()->user()->id)
					->orderBy('created_at', 'desc')
					->get()
					->take(3);

		$carts_count = Cart::where('status', CartStatus::ACTIVE)
						->where('user_id', auth()->user()->id)
						->orderBy('created_at', 'desc')
						->count();

		$carts_total = Cart::where('status', CartStatus::ACTIVE)
						->where('user_id', auth()->user()->id)
						->orderBy('created_at', 'desc')
						->sum('total');
	}
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-31FE9FZ0JW"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-31FE9FZ0JW');
	</script>
	
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Big Four Global">
    <meta name="google-site-verification" content="nhXhX05Rk-oKPtwuF2cnGqGX6123NL4s-g2uSinC8Ec" />

    @include('layouts.partials.meta-tags')
    
    <title>{{ str_replace('_', ' ', env('APP_NAME')) }}</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{ url(env('APP_ICON')) }}" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="{{ url(env('APP_ICON')) }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ url(env('APP_ICON')) }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ url(env('APP_ICON')) }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ url(env('APP_ICON')) }}">
	
    <!-- GOOGLE WEB FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="{{ url('front/css/bootstrap.custom.min.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/style.css') }}" rel="stylesheet">

	<!-- SPECIFIC CSS -->
    <link href="{{ url('front/css/home_1.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/contact.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/listing.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/product_page.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/cart.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/account.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/error_track.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/faq.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/checkout.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/leave_review.css') }}" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="{{ url('front/css/custom.css') }}" rel="stylesheet">

    @include('layouts.front.meta-tags')

</head>

<body>
	
	<div id="page">
		
	<header class="version_2">
		<div class="layer"></div><!-- Mobile menu overlay mask -->
		<div class="main_header">
			<div class="container">
				<div class="row small-gutters">
					<div class="col-xl-3 col-lg-3 d-lg-flex align-items-center">
						<div id="logo">
							<a href="{{ route('site.index') }}"><img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" alt="" width="150" height="35"></a>
						</div>
					</div>
					<nav class="col-xl-6 col-lg-7">
						<a class="open_close" href="javascript:void(0);">
							<div class="hamburger hamburger--spin">
								<div class="hamburger-box">
									<div class="hamburger-inner"></div>
								</div>
							</div>
						</a>
						<!-- Mobile menu button -->
						<div class="main-menu">
							<div id="header_menu">
								<a href="{{ route('site.index') }}"><img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" alt="" width="100" height="35"></a>
								<a href="#" class="open_close" id="close_in"><i class="ti-close"></i></a>
							</div>
							<ul>
								<li class="megamenu submenu">
									<a href="{{ route('shop') }}" class="show-submenu-mega">Shop</a>
									<div class="menu-wrapper">
										<div class="row small-gutters">
											<div class="col-lg-3">
												<h3>Brands</h3>
												<ul>
													@foreach($brands as $brand)
														<li><a href="{{ route('shop.filter', [$brand->name, '*', '*', '*']) }}">{{ $brand->name }}</a></li>
													@endforeach
													<li><a href="#">View All</a></li>
												</ul>
											</div>
											<div class="col-lg-3">
												<h3>Find what you need</h3>
												<ul>
													@foreach($full_categories as $full_category)
														<li><a href="{{ route('shop.filter', ['*', '*', $full_category->id, '*']) }}">{{ $full_category->name }}</a></li>
													@endforeach
												</ul>
											</div>
											<div class="col-lg-3">
												<h3>Components</h3>
												<ul>
													@foreach($components as $component)
														<li><a href="{{ route('shop.filter', ['*', '*', Category::COMPONENTS, $component->id]) }}">{{ $component->name }}</a></li>
													@endforeach
												</ul>
											</div>
											<div class="col-lg-3 d-xl-block d-lg-block d-md-none d-sm-none d-none">
												<div class="banner_menu">
													<a href="{{ route('shop.filter', ['*', '*', Category::APPAREL, '*']) }}">
														<h3>OFFICIAL MERCH STORE</h3>
														<img src="{{ url('front/img/sections/apparel.png') }}" data-src="{{ url('front/img/sections/apparel.png') }}" width="100%" height="550" alt="" class="img-fluid lazy">
													</a>
												</div>
											</div>
										</div>
										<!-- /row -->
									</div>
									<!-- /menu-wrapper -->
								</li>
								<li><a href="{{ route('build') }}">My Dream Rig</a></li>
								<li><a href="https://www.facebook.com/TNCComputerWarehouse" target="_blank">Community</a></li>
								<li><a href="{{ route('site.contact') }}">Contact</a></li>
								<li><a href="{{ route('site.about') }}">About</a></li>
							</ul>
						</div>
						<!--/main-menu -->
					</nav>
					<div class="col-xl-3 col-lg-2 d-lg-flex align-items-center justify-content-end text-end">
						<a class="phone_top" href="tel://9438843343"><strong><span>Need Help?</span>+63 917 148 2260</strong></a>
					</div>
				</div>
				<!-- /row -->
			</div>
		</div>
		<!-- /main_header -->

		<div class="main_nav Sticky">
			<div class="container">
				<div class="row small-gutters">
					<div class="col-xl-3 col-lg-3 col-md-3">
						<nav class="categories">
							<ul class="clearfix">
								<li><span>
										<a href="#">
											<span class="hamburger hamburger--spin">
												<span class="hamburger-box">
													<span class="hamburger-inner"></span>
												</span>
											</span>
											Categories
										</a>
									</span>
									<div id="menu">
										<ul>
											@foreach($full_categories as $full_category)
											@php
												$sub_categories = SubCategory::
																		where('category_id', $full_category->id)
																		->where('status', SubCategoryStatus::ACTIVE)
																		->get();
											@endphp
												<li><a href="{{ route('shop.filter', ['*', '*', $full_category->id, '*']) }}">{{ $full_category->name }}</a>
												@if($sub_categories)
												<ul>
													@foreach($sub_categories as $sub_category)
														
															<li><a href="{{ route('shop.filter', ['*', '*', $sub_category->category->id, $sub_category->id]) }}">{{ $sub_category->name }}</a></li>
														
													@endforeach
												</ul>
												@endif
												</li>
											@endforeach
										</ul>
									</div>
								</li>
							</ul>
						</nav>
					</div>
					<div class="col-xl-6 col-lg-7 col-md-6 d-none d-md-block">
						<div class="custom-search-input">
							<form action="{{ route('shop.search') }}" method="post">
							{{ csrf_field() }}
							<input type="text" name="name" placeholder="Search over {{ $inventories_count }} products" value="{{ old('name') }}">
							<button type="submit"><i class="header-icon_search_custom"></i></button>
							</form>
						</div>
					</div>
					<div class="col-xl-3 col-lg-2 col-md-3">
						<ul class="top_tools">
								<li>
									<div class="dropdown dropdown-cart">
										<a href="{{ route('shop.carts') }}" class="cart_bt"><strong>{{ $carts_count ?? 0 }}</strong></a>
										<div class="dropdown-menu">
											@if (auth()->check())
											<ul>
												@foreach ($carts as $cart)
													<li>
														<a href="{{ route('shop.items.view', [$cart->inventory->id]) }}">
															<figure><img src="{{ url('front/img/products/product_placeholder_square_small.jpg') }}" data-src="{{ url($cart->inventory->item->image) }}" alt="" width="50" height="50" class="lazy"></figure>
															<strong><span>x{{ $cart->qty }} {{ $cart->inventory->item->name }}</span>₱{{ number_format($cart->qty * $cart->price, 2) }}</strong>
														</a>
														<a href="{{ route('shop.carts.delete', [$cart->id]) }}" class="action"><i class="ti-trash"></i></a>
													</li>
												@endforeach
											</ul>
											@endif
											<div class="total_drop">
												<div class="clearfix"><strong>Total</strong><span>₱{{ number_format($carts_total ?? 0, 2) }}</span></div>
												<a href="{{ route('shop.carts') }}" class="btn_1 outline">View Cart</a><a href="{{ route('shop.carts.checkout') }}" class="btn_1">Checkout</a>
											</div>
										</div>
									</div>
									<!-- /dropdown-cart-->
								</li>
							<li>
								<a href="{{ route('shop.wishlists') }}" class="wishlist"><span>Wishlist</span></a>
							</li>
							<li>
								<div class="dropdown dropdown-access">
									@if (auth()->check())
										<a href="{{ route('front.auth.profile') }}" class="access_link"><span>{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</span></a>
									@else
										<a href="#" class="access_link"><span>Account</span></a>
									@endif
									
									<div class="dropdown-menu">
										@if (auth()->check())
											<a href="{{ route('front.auth.profile') }}" class="btn_1">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</a>
										@else
											<a href="#sign-in-dialog" id="sign-in" class="btn_1">Sign In or Sign Up</a>
										@endif
										
										<ul>
											<li><a href="{{ route('front.auth.profile') }}"><i class="ti-user"></i>My Profile</a></li>
											<li><a href="{{ route('shop.payments.track') }}"><i class="ti-truck"></i>Track your Order</a></li>
											<li><a href="{{ route('shop.orders') }}"><i class="ti-package"></i>My Orders</a></li>
											<li><a href="{{ route('site.help') }}"><i class="ti-help-alt"></i>Help and Faq</a></li>
											@if (auth()->check())
												<li><a href="{{ route('logout') }}"><i class="ti-lock"></i>Logout</a></li>
											@endif
										</ul>
									</div>
								</div>
								<!-- /dropdown-access-->
							</li>
							<li>
								<a href="javascript:void(0);" class="btn_search_mob"><span>Search</span></a>
							</li>
							<li>
								<a href="#menu" class="btn_cat_mob">
									<div class="hamburger hamburger--spin" id="hamburger">
										<div class="hamburger-box">
											<div class="hamburger-inner"></div>
										</div>
									</div>
									Categories
								</a>
							</li>
						</ul>
					</div>
				</div>
				<!-- /row -->
			</div>
			<div class="search_mob_wp">
				<form action="{{ route('shop.search') }}" method="post">
					{{ csrf_field() }}
					<input type="text" name="name" class="form-control" placeholder="Search over {{ $inventories_count }} products" value="{{ old('name') }}">
					<input type="submit" class="btn_1 full-width" value="Search">
				</form>
			</div>
			<!-- /search_mobile -->
		</div>
		<!-- /main_nav -->
	</header>
	<!-- /header -->