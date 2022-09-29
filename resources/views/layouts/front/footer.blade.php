@php
	use App\Models\Category;
	use App\Models\CategoryStatus;

	$categories = Category::where('status', CategoryStatus::ACTIVE)
				->orderBy('name', 'asc')
				->get()
				->take(6);
@endphp

<footer class="revealed" style="background: #0F0F0F">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<h3 data-bs-target="#collapse_1">Quick Links</h3>
				<div class="collapse dont-collapse-sm links" id="collapse_1">
					<ul>
						<li><a href="{{ route('shop') }}">Shop</a></li>
						<li><a href="{{ route('build') }}">My Dream Rig</a></li>
						<li><a href="https://www.facebook.com/TNCComputerWarehouse" target="_blank">Community</a></li>
						<li><a href="{{ route('site.contact') }}">Contact</a></li>
						<li><a href="{{ route('site.about') }}">About</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<h3 data-bs-target="#collapse_2">Categories</h3>
				<div class="collapse dont-collapse-sm links" id="collapse_2">
					<ul>
						@foreach($categories as $category)
							<li><a href="{{ route('shop.filter', ['*', '*', $category->id, '*']) }}">{{ $category->name }}</a></li>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
					<h3 data-bs-target="#collapse_3">Contacts</h3>
				<div class="collapse dont-collapse-sm contacts" id="collapse_3">
					<ul>
						<li><i class="ti-home"></i>11 N. Roxas St. Brgy. San Isidro Labrador<br>Quezon City, Philippines</li>
						<li><i class="ti-headphone-alt"></i>712-0089</li>
						<li><i class="ti-email"></i><a href="#">support@bigfourglobal.com</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
					<h3 data-bs-target="#collapse_4">Keep in touch</h3>
				<div class="collapse dont-collapse-sm" id="collapse_4">
					<div id="newsletter">
					    <div class="form-group">
					    	<form action="{{ route('mail.newsletter') }}" method="post">
					    		{{ csrf_field() }}
						        <input type="email" name="email" id="email_newsletter" class="form-control" placeholder="Your email">
						        <button type="submit" id="submit-newsletter"><i class="ti-angle-double-right"></i></button>
					        </form>
					    </div>
					</div>
					<div class="follow_us">
						<h5>Follow Us</h5>
						<ul>
							<li><a href="https://www.facebook.com/TNCComputerWarehouse/"><img src="" data-src="{{ url('front/img/facebook_icon.svg') }}" alt="" class="lazy"></a></li>
							<li><a href="https://twitter.com/TNCPredator"><img src="" data-src="{{ url('front/img/twitter_icon.svg') }}" alt="" class="lazy"></a></li>
							<li><a href="https://www.instagram.com/TNCPredator/"><img src="" data-src="{{ url('front/img/instagram_icon.svg') }}" alt="" class="lazy"></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- /row-->
		<hr>
		<div class="row add_bottom_25">
			<div class="col-lg-6">
				<ul class="footer-selector clearfix">
					<li>
						<div class="styled-select lang-selector">
							<select>
								<option value="English" selected>English</option>
							</select>
						</div>
					</li>
					<li><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="{{ url('front/img/cards_all.svg') }}" alt="" width="198" height="30" class="lazy"></li>
				</ul>
			</div>
			<div class="col-lg-6">
				<ul class="additional_links">
					<li><a href="{{ route('site.terms') }}">Terms and conditions</a></li>
					<li><a href="{{ route('site.privacy') }}">Privacy</a></li>
					<li><span>© {{ date('Y') }} {{ str_replace('_', ' ', env('APP_NAME')) }}</span></li>
				</ul>
			</div>
		</div>
	</div>
</footer>
<!--/footer-->
</div>
<!-- page -->

<div id="toTop"></div><!-- Back to top button -->

	@if (request()->is('/'))
		{{-- @include('layouts.modals.newsletter') --}}
	@endif
	
	@include('layouts.modals.auth.authenticate')
	@include('layouts.modals.alert')

	<!-- COMMON SCRIPTS -->
    <script src="{{ url('front/js/common_scripts.min.js') }}"></script>
    <script src="{{ url('front/js/main.js') }}"></script>
	
	<!-- SPECIFIC SCRIPTS -->
	<script src="{{ url('front/js/carousel-home.min.js') }}"></script>
	
	<!-- SPECIFIC SCRIPTS -->
    <script  src="{{ url('front/js/carousel_with_thumbs.js') }}"></script>

    <!--Start of Tawk.to Script-->
	<script type="text/javascript">
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/6257ff36b0d10b6f3e6d8f5e/1g0jrprjt';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
	</script>
	<!--End of Tawk.to Script-->

</body>
</html>