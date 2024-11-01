<?php
/**
 * Class wrauterwpAdmin
 *
 * This class creates a very simple Options page
 */
class wrauterwpAdmin 
{
 
	/**
	* The security nonce
	*
	* @var string
	*/
	private $_nonce = 'wrauterwp_admin';
 
	/**
	* wrauterwpAdmin constructor.
	*/
	public function __construct() 
	{
		add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
		add_action( 'wp_ajax_wrauterwp_admin_settings', array( $this, 'wrauterwpAdminSettings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'addAdminScripts' ) );
	 
	 }
	 /**
	 * Adds wrauterwp to WordPress Admin Sidebar Menu
	 */
	public function addAdminMenu() {
		$icon = wrauterwp_URL . '/assets/images/icon.png';
		add_menu_page(
			__( 'Wrauter Share ', 'wrauterwp' ),
			__( 'Wrauter Share', 'wrauterwp' ),
			'manage_options',
			'wrauterwp_login',
			array( $this, 'adminlayout' ),
			$icon
		);
	}
	/**
	 * Outputs the Admin Dashboard layout
	 */
	public function adminlayout() {
		$icon1 = '<img src="'. wrauterwp_URL. '/assets/images/wrauterwp.png" alt="wrauterwp logo">';
		$btwrapper = '<img src="'. wrauterwp_URL. '/assets/images/button-wrapper.png" alt="wrauterwp logo">';
		$wrauterwp_settings = wrauterwp::getCredentials();
		$wrauterwp_app_id = (isset($wrauterwp_settings['app_id']) && !empty($wrauterwp_settings['app_id'])) ? $wrauterwp_settings['app_id'] : '';
		$wrauterwp_app_secret = (isset($wrauterwp_settings['app_secret']) && !empty($wrauterwp_settings['app_secret'])) ? $wrauterwp_settings['app_secret'] : '';
		$wrauterwp_domain_url = (isset($wrauterwp_settings['website_url']) && !empty($wrauterwp_settings['website_url'])) ? $wrauterwp_settings['website_url'] : '';
		$redirected_url = (isset($wrauterwp_settings['redirect_user']) && !empty($wrauterwp_settings['redirect_user'])) ? $wrauterwp_settings['redirect_user'] : '';
		?>
		<div class="wrap">			
				<h2><?php _e( 'Settings - Wrauter Share Plugin', 'wrauterwp'); ?></h2>
				
				<h2 class="nav-tab-wrapper">
					<a href="#wrauterwp_button_settings" class="nav-tab" id="wrauterwp_button_settings-tab"><?php _e( 'Share BUTTON Shortcode', 'wrauterwp'); ?></a>
					
					<a href="#wrauterwp_help" class="nav-tab" id="wrauterwp_help-tab"><?php _e( 'Help & Support', 'wrauterwp'); ?></a>
				</h2>
				<div class="updated wrauterwp-message" style="display:none;"></div>
				<div id="wrauterwp" class="metabox-holder">
					<div id="wrauterwp_button_settings" class="group" style="display: block;">
						<div class="inside">
							<div class="wrap wrauterwp-performance">			
								<div class="tabs-holder">
									<div class="tab-nav">
										<input type="hidden" value="share-style-tab" id="tba"></a>
										<ul class="">
										
											<li class="active-tab" data-tabid="share-style-tab">
												<img src="<?php echo wrauterwp_URL; ?>assets/images/share-setup.png" style="float: right;" width="40">
												<span><?php _e('Share Button', 'wrauterwp'); ?></span>
												<p class="margin0">
													<medium><?php _e('Share button usage', 'wrauterwp'); ?></medium>
												</p>
											</li>
										</ul>
									</div>
									<div class="content-tab">
									
							
										<div class="active-tab" id="share-style-tab" style="display: block;">
											<div class="row">
												<div class="col-md-12 wrauterwp__section share-style-tab-section">
													<h3><?php _e( 'Share Button Usage Shortcodes:', 'wrauterwp' ); ?></h3>												
													<div class="form-group">								
														<div class="row">
															<div class="col-md-6">
																<h4><?php _e( 'Shortcode:', 'wrauterwp' ); ?></h4>
																<code>[wrauter_share]</code>
															</div>
															<div class="col-md-6">
																<h4><?php _e( 'Preview:', 'wrauterwp' ); ?></h4>
																<?php echo $icon1; ?>
															</div>									
														</div>
													</div>
													<br/>				
												</div>
											</div>
										</div>
									</div>									
								</div>
							</div>
						</div>
					</div>
					
					<div id="wrauterwp_help" class="group" style="display: block;">
						<div class="inside">
							<div class="wrap wrauterwp-performance">			
								<div class="tabs-holder">								
									<div class="content-tab">
										<div class="single-tab" id="help-tab" style="display: block;">
											<div class="row">
												<div class="col-lg-12 wrauterwp__section help-section">
													<h3 style="color: blue;"><?php _e('Help & Support', 'wrauterwp' ); ?></h3>
													
													<h3><?php _e('Are you looking for any kind of support?', 'wrauterwp' ); ?></h3>
													
													<h3><?php _e( 'Please contact us by creating a support ticket at wordpress plugin page.', 'wrauterwp' ); ?></h3>
													
													
													
													<h3 style="color: green;"><?php _e( 'Thank you for using Our plugin. Please <a href="https://wordpress.org/support/plugin/share-on-wrauter/reviews">RATE</a> on wordpress page that helps a lot.', 'wrauterwp' ); ?></h3>					
												</div>
											</div>
										</div>									
									</div>									
								</div>
							</div>
						</div>
					</div>	
				</div>			
			</div>
			<script>
					jQuery(document).ready(function($) {					
						$('.group').hide();
						var activetab = '';
						if (typeof(localStorage) != 'undefined' ) {
							activetab = localStorage.getItem("activetab");
						}

						//if url has section id as hash then set it as active or override the current local storage value
						if(window.location.hash){
							activetab = window.location.hash;
							if (typeof(localStorage) != 'undefined' ) {
								localStorage.setItem("activetab", activetab);
							}
						}

						if (activetab != '' && $(activetab).length ) {
							$(activetab).fadeIn();
						} else {
							$('.group:first').fadeIn();
						}
						$('.group .collapsed').each(function(){
							$(this).find('input:checked').parent().parent().parent().nextAll().each(
							function(){
								if ($(this).hasClass('last')) {
									$(this).removeClass('hidden');
									return false;
								}
								$(this).filter('.hidden').removeClass('hidden');
							});
						});

						if (activetab != '' && $(activetab + '-tab').length ) {
							$(activetab + '-tab').addClass('nav-tab-active');
						}
						else {
							$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
						}
						$('.nav-tab-wrapper a').click(function(evt) {
							$('.nav-tab-wrapper a').removeClass('nav-tab-active');
							$(this).addClass('nav-tab-active').blur();
							var clicked_group = $(this).attr('href');
							if (typeof(localStorage) != 'undefined' ) {
								localStorage.setItem("activetab", $(this).attr('href'));
							}
							$('.group').hide();
							$(clicked_group).fadeIn();
							evt.preventDefault();
						});
					});
			</script>
		<?php

	}
	/**
	 * Adds Admin Scripts for the Ajax call
	 */
	public function addAdminScripts() {
		$page = isset($_GET['page']);
		if ($page == 'wrauterwp_login') {
			wp_register_style( 'wrauterwp_admin_css', wrauterwp_URL. '/assets/css/wrauterwp-admin.css' );
			wp_enqueue_style( 'wrauterwp_admin_css' );

			wp_enqueue_script( 'wrauterwp-admin', wrauterwp_URL. '/assets/js/admin.js', array(), 1.0 );

			$admin_options = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'_nonce'   => wp_create_nonce( $this->_nonce ),
			);
			wp_localize_script( 'wrauterwp-admin', 'wrauterwp_admin', $admin_options );
		}

	}
	/**
	 * Callback for the Ajax request
	 *
	 * Updates the wrauter App ID and App Secret options
	 */
	public function wrauterwpAdminSettings() {

		if ( wp_verify_nonce( sanitize_text_field($_POST['security']), $this->_nonce ) === false ) {
			die( 'Invalid Request!' );
		}
		$wappid = sanitize_text_field($_POST['app_id']);
		$wappsct = sanitize_text_field($_POST['app_secret']);
		$wbt = sanitize_text_field($_POST['redirect_user']);
		$wurl = esc_url(($_POST['website_url']));
			update_option( 'wrauterwp_login', array(
				'website_url' => $wurl,
				'app_id'     => $wappid,
				'app_secret' => $wappsct,
				'redirect_user' => $wbt,
			) );
			echo _e('Settings Saved!', 'wrauterwp');
			die();
	}

}
 
/*
 * Starts our admin class!
 */
new wrauterwpAdmin();