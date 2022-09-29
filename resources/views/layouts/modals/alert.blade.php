@if($errors->any())
	<div class="popup_wrapper">
		<div class="popup_content newsletter">
			<span class="popup_close">Close</span>
			<div class="row g-0">
			<div class="col-md-5 d-none d-md-flex align-items-center justify-content-center">
	            <figure><img src="{{ url('front/img/banners/1.jpg') }}" alt=""></figure>
	        </div>
	        <div class="col-md-7">
				<div class="content">
					<div class="wrapper">
					<img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" alt="" width="200" height="65">
					<h3>Oops!</h3> 
					<p>There seems to be a problem.</p>
					@foreach($errors->all() as $error)
			            {{ $error }}<br>
			        @endforeach
					</div>
				</div>
	        </div>
	    </div>
			<!-- row -->
		</div>
	</div>
	<!-- /Alert Popup -->
@endif

@if (Session::get('error'))
	<div class="popup_wrapper">
		<div class="popup_content newsletter">
			<span class="popup_close">Close</span>
			<div class="row g-0">
	        <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center">
	            <figure><img src="{{ url('front/img/banners/1.jpg') }}" alt=""></figure>
	        </div>
	        <div class="col-md-7">
				<div class="content">
					<div class="wrapper">
					<img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" alt="" width="200" height="65">
					<h3>Oops!</h3>
					<p>{{ Session::get('error') }}.</p>
					</div>
				</div>
	        </div>
	    </div>
			<!-- row -->
		</div>
	</div>
	<!-- /Alert Popup -->
@endif

