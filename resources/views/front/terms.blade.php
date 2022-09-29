@include('layouts.front.header')
		
<main class="bg_gray">
	<div class="top_banner">
			<div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.8)">
				<div class="container">
					<div class="breadcrumbs">
						<ul>
							<li><a href="#">Home</a></li>
							<li>Terms and Conditions</li>
						</ul>
					</div>
					<h1>Terms and Conditions</h1>
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
				<h2>Terms and Conditions</h2>
				<p class="lead">This document governs your relationship with bigfourglobal.com.</p>
				<p>Access to and use of this Website and the products and services available through this Website (collectively, the "Services") are subject to the following terms, conditions and notices. By using the Services, you are agreeing to all of the Terms of Service, as may be updated by us from time to time. You should check this page regularly to take notice of any changes we may have made to the Terms of Service.</p>
				<p>Access to this Website is permitted on a temporary basis, and we reserve the right to withdraw or amend the Services without notice. We will not be liable if for any reason this Website is unavailable at any time or for any period. From time to time, we may restrict access to some parts or all of this Website. This Website may contain links to other websites, which are not operated by bigfourglobal.com. bigfourglobal.com has no control over the Linked Sites and accepts no responsibility for them or for any loss or damage that may arise from your use of them. Your use of the Linked Sites will be subject to the terms of use and service contained within each such site.</p>

			</div>
		</div>
		<!--/row-->

		<div class="row justify-content-between content_general_row">
			<div class="col-lg-4 text-start">
				<h2>Warranty</h2>
			</div>
		</div>
		<!--/row-->

		<div class="row justify-content-between content_general_row">
			<div class="col-lg-4 text-start">
				<p class="color-text-a">
                  <b>Hardware</b>
                  <ul>
                    <li>{{ str_replace('_', ' ', env('APP_NAME')) }} will provide a minimum ONE-year warranty on all major parts/components purchased. </li>
                    <li>All other products are subject to their original manufactory warranty, terms, and conditions.</li>
                    <li>{{ str_replace('_', ' ', env('APP_NAME')) }} reserves the right to return the product to the vendor for repair or replacement. The customer will be responsible for all shipping fees if required.</li>
                    <li>End of Life (EOL) parts/components cannot be replaced or repaired within the warranty period. {{ str_replace('_', ' ', env('APP_NAME')) }}, all its options, may replace the defective product with an equivalent product, which may be new or reconditioned. All defective products replaced will become the property of {{ str_replace('_', ' ', env('APP_NAME')) }}.</li>
                    <li>{{ str_replace('_', ' ', env('APP_NAME')) }} will provide professional installation of components, if required, at our standard service rates. Customers who choose to install products purchased from {{ str_replace('_', ' ', env('APP_NAME')) }} will be billed separately for any technical support rendered by {{ str_replace('_', ' ', env('APP_NAME')) }} at our standard shop rate.</li>
                    <li>Any refunds or exchanges must occur within seven days of purchase.</li>
                    <li>Any unsatisfactory product but performing equal to or exceeding Manufacturers Specifications will only be returned or replaced if prior authorization is received directly from the Manufacturer.</li>
                    <li>All products returned with no fault are subject to a 25% restocking fee and must be Sealed and have all original packaging.</li>
                    <li>All returns must have the original receipt or a twelve Percent (12)% administration fee.</li>
                  </ul>
                </p>
			</div>

			<div class="col-lg-4 text-start">
				<p>
					<b>Software</b>
	                  <ul>
	                    <li>Due to copyright laws, software in opened packages cannot be returned for any reason.</li>
	                    <li>{{ str_replace('_', ' ', env('APP_NAME')) }} warranty at point of purchase, for any software or software with bundled hardware, is that the software shall be provided "as-is" and with no warranty, express or implied, except that the Manufacturer offers. This includes but is not limited to: operating systems, drivers, utilities, shareware, freeware, firmware, and applications. Suppose {{ str_replace('_', ' ', env('APP_NAME')) }} determines that the fault or problem caused on a customer's computer is wholly or partially related to software corruption, invalid software configuration, or inherent defects in the structure or logic of software. In that case, {{ str_replace('_', ' ', env('APP_NAME')) }} can, at its discretion, bill at the standard shop rate for time-lapsed while configuring or troubleshooting customer-provided software or hardware.</li>
	                    <li>{{ str_replace('_', ' ', env('APP_NAME')) }} cannot accept any liability whatsoever for the loss of data or software. We strongly recommend backing up critical data and programs before returning hardware for service.</li>
	                    <li>Usability issues, especially dealing with operating systems, are not covered by any warranty or technical support policy. Technical support for known and documented procedures will be billed at the standard shop rate.</li>
	                  </ul>
				</p>
			</div>

			<div class="col-lg-4 text-start">
				<p>
					<b>Warranty product will be null and void if</b>
	                  <ul>
	                    <li>Water/Liquid damage at any cause</li>
	                    <li>Jailbroken devices</li>
	                    <li>The product is damaged physically.</li>
	                    <li>Tampering with internal hardware</li>
	                    <li>Subsequent accidental or purposeful drops</li>
	                    <li>Damage caused by electric shortage or surge</li>
	                    <li>Damage resulting from attempted customer repairs</li>
	                    <li>The product is repaired, maintained, modified, and disassembled</li>
	                    <li>Subsequent mishandling or misuse that causes the frame to bend, twist, or crack, and drops</li>
	                  </ul>
				</p>
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