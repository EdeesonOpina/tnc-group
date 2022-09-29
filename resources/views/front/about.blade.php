@include('layouts.front.header')
		
	<main class="bg_gray">
		<div class="top_banner general">
			<div class="opacity-mask d-flex align-items-md-center" data-opacity-mask="rgba(0, 0, 0, 0.7)">
				<div class="container">
					<div class="row justify-content-end">
						<div class="col-lg-8 col-md-6 text-end">
							<h1 style="color: #fff;">Home of the One True Phoenix<br></h1>
							<p class="lead" style="color: #fff;">TNCPC Warehouse Inc. expanded its scope of business lines by engaging in networking, structured cabling, wireless fidelity, emerging computer technologies, and software development to stay competitive and keep abreast with the technology wave that is sweeping our country today.</p>
						</div>
					</div>
				</div>
			</div>
			<img src="{{ url('front/img/top-banners/about.png') }}" class="img-fluid" alt="">
		</div>
		<!--/top_banner-->
		
		<div class="bg_white">
		<div class="container margin_90_0">
			<div class="row justify-content-between align-items-center flex-lg-row-reverse content_general_row">
				<div class="col-lg-5 text-center">
					<figure><img src="{{ url('front/img/about/1.png') }}" data-src="{{ url('front/img/about/1.png') }}" alt="" class="img-fluid lazy" width="100%" height="268"></figure>
				</div>
				<div class="col-lg-6">
					<h2>About Our Company</h2>
					<p class="lead">Established in December of 2020, TNCPC Warehouse, Inc. was recognized as a one-stop shop for computer sales, innovative software, and other related services.</p>
					<p class="lead">The company has committed itself to exert more effort towards a more convenient and assistive premise with the deliverance of practical automated solutions and speedy service response to various daily businesses and personal computer needs.</p>
				</div>
			</div>
			<!--/row-->
		</div>


		<div class="container margin_90_0">
			<div class="row justify-content-between align-items-center content_general_row">
				<div class="col-lg-5 text-start">
					<figure><img src="{{ url('front/img/about/2.png') }}" data-src="{{ url('front/img/about/2.png') }}" alt="" class="img-fluid lazy" width="100%"></figure>
				</div>
				<div class="col-lg-6">
					<h2>TNCPC Warehouse</h2>
					<p class="lead">now, as to meet the demand of ever-progressing technology, the company enhances its services through setting forth in system integration, mobile software development, and Safe City Infrastructure.</p>
					<p class="lead">Paving its way up towards a wider customer base and very improving services has indeed inspired TNCPC Warehouse Inc. to come up with brighter innovations and more dedicated promise - that is, being always available to attend all your needs and be committed to bringing you the VERY BEST.</p>
				</div>
			</div>
			<!--/row-->
		</div>
		<!--/container-->

		<div class="container margin_90_0">
			<div class="row justify-content-between align-items-center content_general_row">
				
			</div>
			<!--/row-->
		</div>
			
		</div>
		<!--/bg_white-->
		<div id="carousel-home">
			<div class="owl-carousel owl-theme">
				<div class="owl-slide cover" style="background-image: url({{ url('front/img/sections/bg_alt.png') }});">
					<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.2)">
						<div class="container">
							<div class="row justify-content-center justify-content-md-start">
								<div class="col-lg-6 static">
									<div class="slide-text white">
										<h2 class="owl-slide-animated owl-slide-title">"Awesome Experience"</h2>
										<p class="owl-slide-animated owl-slide-subtitle">
											<em>His dolor docendi fuisset ad, movet mucius diceret et qui. Esse ferri integre sit id. Nec iusto eleifend definitionem ne, persius argumentum sed ut.</em>
										</p>
										<div class="owl-slide-animated owl-slide-cta"><small>Susan - Photographer</small></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/owl-slide-->
				<div class="owl-slide cover" style="background-image: url({{ url('front/img/sections/1.png') }});">
					<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.4)">
						<div class="container">
							<div class="row justify-content-center justify-content-md-start">
								<div class="col-lg-6 static">
									<div class="slide-text white">
										<h2 class="owl-slide-animated owl-slide-title">"Great Support"</h2>
										<p class="owl-slide-animated owl-slide-subtitle">
											<em>His dolor docendi fuisset ad, movet mucius diceret et qui. Esse ferri integre sit id. Nec iusto eleifend definitionem ne.</em>
										</p>
										<div class="owl-slide-animated owl-slide-cta">Mary - Doctor</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/owl-slide-->
				<div class="owl-slide cover" style="background-image: url({{ url('front/img/sections/bg.png') }});">
					<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.1)">
						<div class="container">
							<div class="row justify-content-center justify-content-md-start">
								<div class="col-lg-6 static">
									<div class="slide-text white">
										<h2 class="owl-slide-animated owl-slide-title">"Satisfied"</h2>
										<p class="owl-slide-animated owl-slide-subtitle">
											<em>His dolor docendi fuisset ad, movet mucius diceret et qui. Esse ferri integre sit id. Nec iusto eleifend definitionem ne.</em>
										</p>
										<div class="owl-slide-animated owl-slide-cta">Mary - Doctor</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/owl-slide-->
			</div>
			<div id="icon_drag_mobile"></div>
		</div>
		<!--/carousel-->
	</main>
	<!--/main-->
	
@include('layouts.front.footer')