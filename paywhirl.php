<?php defined('ABSPATH') or die();


/*
 * Plugin Name: Paywhirl for Wordpress
 * Plugin URI: http://www.paywhirl.com
 * Description: Provides Paywhirl functionality for Wordpress.
 * Version: 1.2
 * Author: Paywhirl
 * Author URI: http://www.paywhirl.com
 * License: GPL2
 */


// Define the paywhirl directory
define('PAYWHIRL_DIR', plugin_dir_path(__FILE__));

// Load includes
include plugin_dir_path(__FILE__).'includes/rewrite.php';
include plugin_dir_path(__FILE__).'includes/database.php';
include plugin_dir_path(__FILE__).'includes/shortcodes.php';
include plugin_dir_path(__FILE__).'includes/admin_menu.php';
include plugin_dir_path(__FILE__).'includes/admin_pages.php';
include plugin_dir_path(__FILE__).'includes/admin_styles.php';
include plugin_dir_path(__FILE__).'includes/admin_scripts.php';