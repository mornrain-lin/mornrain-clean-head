<?php
/**
 * Plugin Name: MornRain Clean Head
 * Plugin URI: https://github.com/mornrain-lin/mornrain-clean-head
 * Description: Removes redundant output from wp_head: emoji scripts, generator meta, RSD and WLW manifest links, shortlinks, oEmbed discovery and asset version query strings.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: MornRain
 * Author URI: https://github.com/mornrain-lin
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mornrain-clean-head
 * Domain Path: /languages
 *
 * @package Mornrain_Clean_Head
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'MORNRAIN_CLEAN_HEAD_VERSION', '1.0.0' );
define( 'MORNRAIN_CLEAN_HEAD_FILE', __FILE__ );
define( 'MORNRAIN_CLEAN_HEAD_PATH', plugin_dir_path( __FILE__ ) );
define( 'MORNRAIN_CLEAN_HEAD_URL', plugin_dir_url( __FILE__ ) );
define( 'MORNRAIN_CLEAN_HEAD_OPTION', 'mornrain_clean_head_settings' );

require_once MORNRAIN_CLEAN_HEAD_PATH . 'includes/functions-clean-head.php';
require_once MORNRAIN_CLEAN_HEAD_PATH . 'includes/class-mornrain-clean-head.php';

if ( ! function_exists( 'mornrain_clean_head' ) ) :
	/**
	 * Return the shared plugin instance.
	 *
	 * @since 1.0.0
	 * @return Mornrain_Clean_Head
	 */
	function mornrain_clean_head() {
		return Mornrain_Clean_Head::instance();
	}
endif;

register_activation_hook( __FILE__, array( 'Mornrain_Clean_Head', 'on_activate' ) );
register_uninstall_hook( __FILE__, array( 'Mornrain_Clean_Head', 'on_uninstall' ) );

mornrain_clean_head();
