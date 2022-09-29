<!-- Sign In Modal -->
<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">
	<div class="modal_header">
		<h3>Sign In</h3>
	</div>
		<div class="sign-in-wrapper">
			<!-- <a href="{{ route('socialite.redirect', ['facebook']) }}" class="social_bt facebook">Login with Facebook</a> -->
			<a href="{{ route('socialite.redirect', ['google']) }}" class="social_bt google">Login with Google</a>
			<div class="divider"><span>Or</span></div>
			<form action="{{ route('login') }}" method="post">
				{{ csrf_field() }}
				<div class="form-group">
					<label>Email</label>
					<input type="email" class="form-control" name="email" id="email">
					<i class="ti-email"></i>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" name="password" id="password" value="">
					<i class="ti-lock"></i>
				</div>
				<div class="clearfix add_bottom_15">
					<div class="checkboxes float-start">
						<label class="container_check">Remember me
						  <input type="checkbox">
						  <span class="checkmark"></span>
						</label>
					</div>
					<div class="float-end mt-1"><a id="forgot" href="javascript:void(0);">Forgot Password?</a></div>
				</div>
				<div class="text-center">
					<input type="submit" value="Log In" class="btn_1 full-width">
					Don’t have an account? <a href="{{ route('register') }}">Sign up</a>
				</div>
			</form>
			<form action="{{ route('auth.reset') }}" method="post" novalidate>
	            {{ csrf_field() }}
				<div id="forgot_pw">
					<div class="form-group">
						<label>Please confirm login email below</label>
						<input type="email" name="email" class="form-control" name="email_forgot" id="email_forgot">
						<i class="ti-email"></i>
					</div>
					<p>You will receive an email containing a link allowing you to reset your password to a new preferred one.</p>
					<div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
				</div>
			</form>
		</div>
	<!--form -->
</div>
<!-- /Sign In Modal -->