@include('layouts.front.header')
		
<main class="bg_gray">
	<div id="error_page">
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-xl-7 col-lg-9">
					<img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" alt="" class="img-fluid" width="400" height="212"><br><br>
					<p>This page is still under construction</p>
				</div>
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /error_page -->
</main>
<!--/main-->
	
@include('layouts.front.footer')