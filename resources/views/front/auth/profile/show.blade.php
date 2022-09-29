@include('layouts.front.header')
		
<main class="bg_gray">
		
	<div class="container margin_30">
		<div class="page_header">
			<div class="breadcrumbs">
				<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Account</a></li>
					<li>My Profile</li>
				</ul>
		</div>
		<h1>My Profile</h1>
	</div>
	<!-- /page_header -->
			<div class="row">
			<div class="col-xl-8 col-lg-8 col-md-8">
				<div class="box_account">
					<div class="form_container">

						<div class="row">
							<div class="col">
								<div class="form-group">
									<label>Name</label><br>
									{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Email</label><br>
									{{ auth()->user()->email }}
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<div class="form-group">
									<label>Mobile</label><br>
									{{ auth()->user()->mobile }}
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Phone</label><br>
									{{ auth()->user()->phone }}
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<div class="form-group">
									<label>Country</label><br>
									{{ auth()->user()->country->name }}
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<div class="form-group">
									<label>Line Address 1</label><br>
									{{ auth()->user()->line_address_1 }}
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Line Address 2</label><br>
									{{ auth()->user()->line_address_2 }}
								</div>
							</div>
						</div>

					</div>
					<!-- /form_container -->
				</div>
				<!-- /box_account -->
			</div>
		</div>
		<!-- /row -->
		</div>
		<!-- /container -->
	</main>
	<!--/main-->
	
@include('layouts.front.footer')