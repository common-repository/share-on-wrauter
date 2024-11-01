<?php
/**
 * Class wrauterwp
 *
 * This class creates a very simple Options page
 */
class wrauterwp{

    /**
     * wrauterwp | Being Social constructor.
     */
    public function __construct()
    {
		add_shortcode( 'wrauter_login', array($this, 'wrauterlogin') );
		add_shortcode( 'wrauter_share', array($this, 'wrauterwpShare') );        
		add_action( 'wp_enqueue_scripts', array($this, 'addButtonCSS'));
		// Callback URL
        add_action( 'init', array($this, 'apiCallback'));
    }

	private $access_token;	
	
    /**
     * Callback URL used by the API
     *
     * @var string
     */

	/**
	 * Returns the wrauter credentials as an array containing app_id and app_secret
	 *
	 * @return array
	 */
	static function getCredentials() {
	   return get_option( 'wrauterwp_login', array() );
	}

	/**
	 * Returns the callback URL
	 *
	 * @return string
	 */
	static function getCallbackUrl() {
	   return get_admin_url( null, 'admin-ajax.php?action=wrauterwp_login' );
	}

	/**
	 * Render the shortcode [wrauterwp_login]
	 *
	 * It displays our Login / Register button
	 */
	public function wrauterlogin($atts) {
		// get the users entered shortcode attributes
			$atts = shortcode_atts(array(
				'button_type' => 'icon',
				'loggedin_text' => 'You are already logged in!',
			), $atts);
		// No need for the button is the user is already logged
		if(is_user_logged_in()) {
			if (isset($atts['loggedin_text'])) {
				$html = '<p>'. $atts['loggedin_text'] .'</p>';
				return $html; // Write it down as user already logged in.
			} else {
				return; // exit as user already logged in.
			}
		}
			//return;
		// Different labels according to whether the user is allowed to register or not
		if (get_option( 'users_can_register' )) {
			$button_label = __('Login or Register with wrauter', 'wrauterwp');
		} else {
			$button_label = __('Login with wrauter', 'wrauterwp');
		}

		$credentials = self::getCredentials();

		   // Only if we have some credentials, ideally an Exception would be thrown here
		  
		   if ($atts['button_type'] == 'wrapper') {
			  // Button markup				
				$logo = wrauterwp_URL. '/assets/images/button-wrapper.png';
				$html = '<div id="wrauterwp-wrapper">';
				$html .= '<a href="'.$this->getLoginUrl().'" class="btn" id="wrauterwp-button">'. $logo . $button_label .'</a>';
				$html .= '</div>';	  
		   } else if ($atts['button_type'] == 'icon') {	
				$logo = wrauterwp_URL. '/assets/images/wrauterwp.png';
				ob_start();
				?>				
				<div class="content-share__item lovez buzz-share-button">
					<button class="wrauterwp-share" style="background: url(<?php echo $logo?>) no-repeat;" onclick="location.href='<?php echo $this->getLoginUrl()?>';">
					</button>
				</div>
				<?php
				$html = ob_get_clean();			
		   }

		// Write it down
		return $html;
	}
	public function wrauterwpShare() {
		$full_url= get_permalink(get_the_ID());	
		$credentials = self::getCredentials();
		$web_url = (isset($credentials['website_url']) && !empty($credentials['website_url'])) ? $credentials['website_url'] : '';
		$logo = wrauterwp_URL. '/assets/images/wrautershare.svg';		
		ob_start();
		?>
			<div class="content-share__item lovez buzz-share-button">
				<button class="wrauterwp-share" style="background: url(<?php echo $logo?>) no-repeat; background-size: contain;" onclick="window.open('https://wrauter.com/sharer?url=<?php echo $full_url ?>', 'Share on wrauterwp', 'height=600,width=800');">
				</button >
			</div>
		<?php
		$contents = ob_get_clean();
		return $contents;
	}
	/**
	 * Login URL to wrauter API
	 *
	 * @return string
	 */
	private function getLoginUrl() {
		$credentials = self::getCredentials();
		$web_url = (isset($credentials['website_url']) && !empty($credentials['website_url'])) ? $credentials['website_url'] : '';

	   // Only if we have some credentials, ideally an Exception would be thrown here
	   if(!isset($credentials['app_id']))
		  return null;


		$url = $web_url . 'oauth?app_id=' .$credentials['app_id'];

		return esc_url($url);

	}
	/**
	 * Get user details through the wrauter API
	 *
	 * @link https://demo.wrauter.com/developers
	 */
	private function getUserDetails($wrauterwp)
	{  
			$credentials = self::getCredentials();
			$web_url = (isset($credentials['website_url']) && !empty($credentials['website_url'])) ? $credentials['website_url'] : '';
			$type = "get_user_data"; // or posts_data
			//$response = file_get_contents("{$web_url}app_api?access_token={$wrauterwp}&type={$type}");
			$response = wp_remote_get("{$web_url}app_api?access_token={$wrauterwp}&type={$type}");
			$body = wp_remote_retrieve_body( $response );
				   
		return $body;

	}
	/**
	 * Login an user to WordPress
	 *
	 * @link https://codex.wordpress.org/Function_Reference/get_users
	 * @return bool|void
	 */
	private function loginUser($wrauterwp_user) {

		$credentials = self::getCredentials();
		$redirect_user = (isset($credentials['redirect_user']) && !empty($credentials['redirect_user'])) ? $credentials['redirect_user'] : '';
		
		// We look for the `wrauterwp_login_email` to see if there is any match
		$wp_users = get_users(array(
			'meta_key'     => 'wrauterwp_login_email',
			'meta_value'   => $wrauterwp_user['email'],
			'number'       => 1,
			'count_total'  => false,
			'fields'       => 'email',
		));

		if(empty($wp_users[0])) {
			return false;
		}

		// Log the user ?
		wp_set_auth_cookie( $wp_users[0] );
		if ($redirect_user) {
			wp_safe_redirect( $redirect_user );
			exit();
		} else {
			wp_safe_redirect( site_url() );
			exit();
		}

	}
	/**
	 * Create a new WordPress account using wrauter Details
	 */
	private function createUser($wrauterwp_user) {
		
		$credentials = self::getCredentials();
		$redirect_user = (isset($credentials['redirect_user']) && !empty($credentials['redirect_user'])) ? $credentials['redirect_user'] : '';
		
		// Create an username
		$wrauterwpemail = $wrauterwp_user['email'];
		$parts = explode("@", $wrauterwpemail);
		$username = $parts[0];
		//check if the username already exists
		$exist_username = username_exists( $username );
		if ( $exist_username ) {
			$username = $parts[0] . '_' . date("YmdHms");
		}
		// Creating our user
		$new_user = wp_create_user($username, wp_generate_password(), $wrauterwp_user['email']);

		if(is_wp_error($new_user)) {
		   
		    echo "Error while creating user!";
			if ($redirect_user) {
				wp_safe_redirect( $redirect_user );
				exit();
			} else {
				wp_safe_redirect( site_url() );
				exit();
			}
	   }
		// Setting the meta
		update_user_meta( $new_user, 'first_name', $wrauterwp_user['first_name'] );
		update_user_meta( $new_user, 'last_name', $wrauterwp_user['last_name'] );
		update_user_meta( $new_user, 'wrauterwp_login_email', $wrauterwp_user['email'] );

		// Log the user ?
		wp_set_auth_cookie( $new_user );

	}
	public function apiCallback() {
		if ( isset($_GET['code']) ) {	
			$credentials = self::getCredentials();
			$found = sanitize_text_field($_GET['code']);    
			// Only if we have some credentials, ideally an Exception would be thrown here    
			if( !isset($credentials['app_id']) || !isset($credentials['app_secret']) ) {
				exit();
			} else {
				$web_url = (isset($credentials['website_url']) && !empty($credentials['website_url'])) ? $credentials['website_url'] : '';
				$redirected_url = (isset($credentials['redirect_user']) && !empty($credentials['redirect_user'])) ? $credentials['redirect_user'] : '';
				$get_wrauterwp_data = wp_remote_get("{$web_url}authorize?app_id={$credentials['app_id']}&app_secret={$credentials['app_secret']}&code={$found}");
				$body = wp_remote_retrieve_body( $get_wrauterwp_data );
				
				
				$json = json_decode($body, true);
				if (!empty($json['access_token'])) {
					$access_token = $json['access_token']; // your access token
					$wl_data = $this->getUserDetails($access_token);
					$enc_wl_data=json_decode($wl_data, true);
					$wrauterwp_details=$enc_wl_data['user_data'];
				// We first try to login the user
					$this->loginUser($wrauterwp_details);

				// Otherwise, we create a new account
					$this->createUser($wrauterwp_details);

				// Redirect the user succesful login
					if ($redirected_url) {
						wp_safe_redirect( $redirected_url );
						exit();
					} else {
						wp_safe_redirect( site_url() );
						exit();
					}
				}
			}
		} else {
			return null;
		}		
	}
	public function addButtonCSS() {
		wp_enqueue_style( 'wrauterwp-button', wrauterwp_URL. '/assets/css/button-style.css' );
	}
}
/*
 * Starts our plugins!
 */
 new wrauterwp();