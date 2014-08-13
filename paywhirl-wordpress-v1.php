<?php
/*
Plugin Name: PayWhirl
Plugin URI: https://paywhirl.com
Description: PAYWHIRL makes it incredibly easy to create and manage recurring subscription plans. It removes the hard work necessary to code all the views that allow your users to sign-up for memberships, create user accounts and manage their customer subscriptions. Anyone with an existing website can setup our widget in just a few minutes and start collecting recurring payments immediately, whereas with custom development, it can weeks or months to integrate.
Version: 0.1
Author: Yovigo, Inc
Author Email: team@yovigo.com
License:

  Copyright 2011 Yovigo, Inc (team@yovigo.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class Paywhirl {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'Paywhirl';
	const slug = 'paywhirl';
	
	/**
	 * Constructor
	 */
	function __construct() {
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_paywhirl' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_paywhirl' ) );
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_paywhirl() {
		// do not generate any output here
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_paywhirl() {
		// Setup localization
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// Register the shortcode [paywhirl]
		add_shortcode( 'paywhirl', array( &$this, 'render_shortcode' ) );
	
		if ( is_admin() ) {
			//this will run when in the WordPress admin
		} else {
			//this will run when on the frontend

		}
 
	}

	function action_callback_method_name() {
		// TODO define your action method here
	}

	function filter_callback_method_name() {
		// TODO define your filter method here
	}

	function render_shortcode($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'key' => '', //foo is a default value
			), $atts));
		// you can now access the attribute values using $attr1 and $attr2
		if(trim($atts['key']) == ""){
			echo "Please provide an API Key. You can retrieve your API Key by visiting <a href='https://paywhirl.com/my-account'>https://paywhirl.com/my-account</a>.<br><br>

			Once you have your key, use your place your shortcode in wordpress like this:<br><br> [paywhirl key=1234567890]
			";
		} else {
		?>
		
		<script type='text/javascript' src="https://paywhirl.com/js/widget.js"></script>
		<iframe style='width:100%; height:auto; border:0; overflow:hidden;' id='paywhirl_frame' scrolling='no' src="https://paywhirl.com/widget?api_key=<?php echo $atts['key']; ?>"></iframe>

		
		<?php
		}
	}
  
	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {
			$this->load_file( self::slug . '-admin-script', '/js/admin.js', true );
			$this->load_file( self::slug . '-admin-style', '/css/admin.css' );
		} else {
			$this->load_file( self::slug . '-script', '/js/widget.js', true );
			$this->load_file( self::slug . '-style', '/css/widget.css' );
		} // end if/else
	} // end register_scripts_and_styles
	
	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file( $name, $file_path, $is_script = false ) {

		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__) . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, array('jquery') ); //depends on jquery
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

	} // end load_file
  
} // end class
new Paywhirl();

?>