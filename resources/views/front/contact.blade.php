@include('layouts.front.header')
		
	<main class="bg_gray">
	
			<div class="container margin_60">
				<div class="main_title">
					<h2>Contact Us</h2>
					<p>Feel free to send us a message</p>
				</div>
				<div class="row justify-content-center">
					<div class="col-lg-4">
						<div class="box_contacts">
							<i class="ti-support"></i>
							<h2>Help Center</h2>
							<a href="#">712-4675</a>, <a href="#">712-0089</a><br><a href="#">ronalyn@bigfourglobal.com</a>
							<small>MON to FRI 9am-6pm SAT 9am-5pm</small>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="box_contacts">
							<i class="ti-map-alt"></i>
							<h2>Find us in</h2>
							<div>11 N. Roxas St. Brgy. San Isidro Labrador</div>
							<div>Quezon City, Philippines</div><br>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="box_contacts">
							<i class="ti-package"></i>
							<h2>Connect with us</h2>
							<div class="row">
								<div class="col">
									&nbsp;
								</div>
								<div class="col">
									<a href="https://www.facebook.com/TNCComputerWarehouse/"><img src="" data-src="{{ url('front/img/facebook_icon.svg') }}" alt="" class="lazy" width="80%"></a>
								</div>
								<div class="col">
									<a href="https://twitter.com/TNCPredator"><img src="" data-src="{{ url('front/img/twitter_icon.svg') }}" alt="" class="lazy" width="80%"></a>
								</div>
								<div class="col">
									<a href="https://www.instagram.com/TNCPredator/"><img src="" data-src="{{ url('front/img/instagram_icon.svg') }}" alt="" class="lazy" width="80%"></a>
								</div>
								<div class="col">
									&nbsp;
								</div>
							</div>
							<br>
						</div>
					</div>
				</div>
				<!-- /row -->				
			</div>
			<!-- /container -->
		<div class="bg_white">
			<div class="container margin_60_35">
				<h4 class="pb-3">Drop Us a Line</h4>
				<form action="{{ route('mail.contact') }}" method="post">
						{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-4 col-md-6 add_bottom_25">
						<div class="form-group">
							<input class="form-control" name="name" type="text" placeholder="Name *">
						</div>
						<div class="form-group">
							<input class="form-control" name="email" type="email" placeholder="Email *">
						</div>
						<div class="form-group">
							<textarea class="form-control" name="description" style="height: 150px;" placeholder="Message *"></textarea>
						</div>
						<div class="form-group">
							<input class="btn_1 full-width" type="submit" value="Submit">
						</div>
					</div>
					
					<div class="col-lg-8 col-md-6 add_bottom_25">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.5983366272526!2d120.99505491535197!3d14.621942580472144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b60e45e1ce2f%3A0x4a5b24ef00bd5f83!2sBig%20Four%20Global%20Technologies%20Inc.!5e0!3m2!1sen!2sph!4v1649390352946!5m2!1sen!2sph" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
					</div>
				</div>
				<!-- /row -->
			</form>
			</div>
			<!-- /container -->
		</div>
		<!-- /bg_white -->
	</main>
	<!--/main-->
	
@include('layouts.front.footer')