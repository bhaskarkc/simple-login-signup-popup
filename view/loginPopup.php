<div class="lightbox" id="xlinkPop" style='display: none;'>
	<div class="light-box-container">
		<div class="cus-popup">

			<!-- Login popup -->
			<div class="login-popup">
				<div class="popup-logo"><?php echo bloginfo(); ?></div>
				<p> <?php echo bloginfo('description'); ?> </p>

				<div class="login-button-wrapper">
					<?php echo do_shortcode( '[fb_login]' ); ?>
					<!-- <button id="facebook-btn" class="facebook-login-btn cus-btn">Join with Facebook <i class="ion-social-facebook"></i></button> -->
					<button id="join-email-btn"class="facebook-login-btn cus-btn">Join with Email <i class="ion-ios-email"></i></button>
				</div>
				<p class="promise">No spam. Ever.</p>
				<h3>Already a Member? <a href="#" class="signin-link">Sign In Here</a> </h3>

				<p class="hints">By joining, you agree to our <a href="#!">terms of service.</a> </p>

			</div><!--login-popup ends-->

			<!-- Registration Popup -->
			<div class="registration-popup" style="display:none;">
				<form name='register_swivel' method='post' action="">
					<h3 class="popup-title">Register</h3>
					<input type="email" class="form-control" name="register_swivel[email]" placeholder="example@gmail.com">
					<input type="password" class="form-control" name="register_swivel[pwd]" placeholder="password">
					<button type="submit" class="btn">Register</button>
					<div class="registration-close"><i class="ion-close"></i></div>
				</form>
			</div><!--registration-popup ends-->

			<div class="signin" style="display:none;">

				<h3 class="popup-title">Sign In</h3>
				<div class="login-button-wrapper">
					<!-- <button id="facebook-btn" class="facebook-login-btn cus-btn">Join with Facebook <i class="ion-social-facebook"></i></button> -->
					<?php echo do_shortcode( '[fb_login]' ); ?>

					<h3 class="text-center">OR</h3>
					<form name="login_swivel" method="post" action="">
						<input type="email" name="login_swivel[username]" class="form-control" placeholder="Email">
						<input type="password" name="login_swivel[password]" class="form-control" placeholder="password">
						<div class="checkbox">
							<label><input type="checkbox" name="login_swivel[remember]"> Remember me</label>
						</div>
						<button type="submit" class="btn">Login</button>
						<!-- <button id="join-email-btn"class="facebook-login-btn cus-btn">Join with Email <i class="ion-ios-email"></i></button> -->
					</form>
					<h3>Not A Member Yet ? <a href="#" class="signup-link">Sign Up</a> </h3>

					<p class="hints"><a href="#!">Forgot Your Password ?</a> </p>
					<div class="signin-close"><i class="ion-close"></i></div>
				</div>

			</div><!--signin ends-->

		</div><!--popup ends-->
	</div>
</div>