<?php
/**
 * Plugin Name:       Share On Wrauter
 * Description:       Share You Posts on India's Own Social Media "Wrauter" 
 * Version:           1.0
 * Author:            Aadyasha Infotech
 * Author URI:        https://www.aadyashainfotech.com/
 * Text Domain:       shareonwrauter
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Abort if this file is called directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Plugin constants
 */

if(!defined('wrauterwp_URL'))
	define('wrauterwp_URL', plugin_dir_url( __FILE__ ));
if(!defined('wrauterwp_PATH'))
	define('wrauterwp_PATH', plugin_dir_path( __FILE__ ));

/*
 * Redirect after activation
 */
function wrauterwp_redirect_activation($plugin) {
	$redirect_url = admin_url( 'admin.php?page=wrauterwp_login' );
	if( $plugin == plugin_basename( __FILE__ ) ) {
		exit( wp_redirect( $redirect_url ) );
	}	
}
add_action( 'activated_plugin', 'wrauterwp_redirect_activation', 10, 1);

/*
 * Import the plugin classes
 */
include_once dirname( __FILE__ ) . '/classes/wrauterwp.php';
include_once dirname( __FILE__ ) . '/classes/wrauterwpadmin.php';


function wrauter_share( $content ) {
  global $post;
  if( ! $post instanceof WP_Post ) return $content;

  switch( $post->post_type ) {
    case 'post':
      return $content . '[wrauter_share]My content[/wrauter_share]';

    case 'page':
      return $content . '[wrauter_share]My content[/wrauter_share]';

    default:
      return $content;
  }
}

add_filter("the_content", "wrauter_share");
