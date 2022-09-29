@include('layouts.front.header')
		
<main class="bg_gray">
	<div class="top_banner">
			<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.8)">
				<div class="container">
					<div class="breadcrumbs">
						<ul>
							<li><a href="#">Home</a></li>
							<li>Privacy Policy</li>
						</ul>
					</div>
					<h1>Privacy Policy</h1>
				</div>
			</div>
			<img src="{{ url('front/img/sections/bg.jpg') }}" class="img-fluid" alt="">
		</div>
		<!-- /top_banner -->	
	
	<div class="bg_white">
	<div class="container margin_90_0">
		<!--/row-->
		<div class="row justify-content-between content_general_row">
			<div class="col-lg-4 text-start">
				<figure><img src="img/about_placeholder.jpg" data-src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" alt="" class="img-fluid lazy" width="350" height="268"></figure>
			</div>
			<div class="col-lg-8">
				<h2>Privacy Policy</h2>
				<p>Our privacy policy, which sets out how we will use your information, can be found at bigfourglobal.com/privacy. By using this Website, you consent to the processing described therein and warrant that all data provided by you is accurate.</p>

				<strong>Prohibitions</strong>
				<p>You must not misuse this Website. You will not: commit or encourage a criminal offense; transmit or distribute a virus, trojan, worm, logic bomb or any other material which is malicious, technologically harmful, in breach of confidence or in any way offensive or obscene; hack into any aspect of the Service; corrupt data; cause annoyance to other users; infringe upon the rights of any other person's proprietary rights; send any unsolicited advertising or promotional material, commonly referred to as "spam"; or attempt to affect the performance or functionality of any computer facilities of or accessed through this Website. Breaching this provision would constitute a criminal offense and bigfourglobal.com will report any such breach to the relevant law enforcement authorities and disclose your identity to them.</p>
				<p>We will not be liable for any loss or damage caused by a distributed denial-of-service attack, viruses or other technologically harmful material that may infect your computer equipment, computer programs, data or other proprietary material due to your use of this Website or to your downloading of any material posted on it, or on any website linked to it.</p>

				<strong>Intellectual Property, Software and Content</strong>
				<p>The intellectual property rights in all software and content (including photographic images) made available to you on or through this Website remains the property of bigfourglobal.com or its licensors and are protected by copyright laws and treaties around the world. All such rights are reserved by bigfourglobal.com and its licensors. You may store, print and display the content supplied solely for your own personal use. You are not permitted to publish, manipulate, distribute or otherwise reproduce, in any format, any of the content or copies of the content supplied to you or which appears on this Website nor may you use any such content in connection with any business or commercial enterprise.</p>

				<strong>Terms of Sale</strong>
                <p>By placing an order you are offering to purchase a product on and subject to the following terms and conditions. All orders are subject to availability and confirmation of the order price.
                  Dispatch times may vary according to availability and subject to any delays resulting from postal delays or force majeure for which we will not be responsible.</p>

                <p>In order to contract with bigfourglobal.com you must be over 18 years of age and possess a valid credit or debit card issued by a bank acceptable to us. bigfourglobal.com retains the right to refuse any request made by you. If your order is accepted we will inform you by email and we will confirm the identity of the party which you have contracted with. This will usually be bigfourglobal.com or may in some cases be a third party. Where a contract is made with a third party bigfourglobal.com is not acting as either agent or principal and the contract is made between yourself and that third party and will be subject to the terms of sale which they supply you. When placing an order you undertake that all details you provide to us are true and accurate, that you are an authorized user of the credit or debit card used to place your order and that there are sufficient funds to cover the cost of the goods. The cost of foreign products and services may fluctuate. All prices advertised are subject to such changes.</p>

                <strong>Pricing Policy</strong>
                <p>All prices showed on the website are subject to change without any prior notice. This may be due to the consequence of supplier price changes or due to current market situations.</p>

                <p>The prices may or may not be applicable for some or all physical stores of {{ str_replace('_', ' ', env('APP_NAME')) }} and may or may not be applicable for some modes of payment.</p>

                <p>{{ str_replace('_', ' ', env('APP_NAME')) }} is determined to ensure accurate price information; however, the inaccuracy may still occur, and the price of your order will be validated as part of the acceptance procedure. If the price changed or has some inaccuracy, we may, at our discretion, either contact you for further instructions or cancel your order and notify you of such cancellation. {{ str_replace('_', ' ', env('APP_NAME')) }} reserves the right to refuse or cancel any such orders whether or not the order has been confirmed and your card charged.</p>

			</div>
		</div>
		<!--/row-->

		<div class="container margin_90_0">
			<div class="row justify-content-between align-items-center content_general_row">
				
			</div>
			<!--/row-->
		</div>
	</div>
	<!--/container-->
		
	</div>

</main>
<!--/main-->
	
@include('layouts.front.footer')