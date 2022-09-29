@include('layouts.front.header')
		
<main class="bg_gray">
	<div class="container">
        <div class="row justify-content-center">
			<div class="col-md-5">
				<div id="confirm">
					<div class="icon icon--order-success svg add_bottom_15">
						<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
							<g fill="none" stroke="#8EC343" stroke-width="2">
								<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
								<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
							</g>
						</svg>
					</div>
				<h2>Message sent!</h2>
				<p>Our team will reach out to you soon.</p>
				You will be redirected in 
				<div id="countdown">5 seconds</div>
				</div>
			</div>
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</main>
<!--/main-->

<script type="text/javascript">
	setTimeout(function () {
	   window.location.href = "{{ route('build.result') }}"; //will redirect to your blog page (an ex: blog.html)
	}, 5000); //will call the function after 2 secs.
</script>

<script type="text/javascript">
	var timeleft = 5;
	var downloadTimer = setInterval(function(){
	  if(timeleft <= 0){
	    clearInterval(downloadTimer);
	    document.getElementById("countdown").innerHTML = "";
	  } else {
	    document.getElementById("countdown").innerHTML = timeleft + " seconds";
	  }
	  timeleft -= 1;
	}, 1000);
</script>
	
@include('layouts.front.footer')