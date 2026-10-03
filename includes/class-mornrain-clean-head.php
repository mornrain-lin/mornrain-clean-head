<?php
/**
 * Main plugin class.
 *
 * @package Mornrain_Clean_Head
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mornrain_Clean_Head' ) ) :
	/**
	 * Detaches the default wp_head and asset callbacks that add redundant output.
	 *
	 * @since 1.0.0
	 */
	final class Mornrain_Clean_Head {

		/**
		 * Shared instance.
		 *
		 * @since 1.0.0
		 * @var Mornrain_Clean_Head|null
		 */
		private static $instance = null;

		/**
		 * Retrieve the shared instance, creating it on first call.
		 *
		 * @since 1.0.0
		 * @return Mornrain_Clean_Head
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Wire up the plugin.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			$this->includes();
			$this->hooks();
		}

		/**
		 * Load module files.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function includes() {
			require_once MORNRAIN_CLEAN_HEAD_PATH . 'includes/class-mornrain-clean-head-emoji.php';
			require_once MORNRAIN_CLEAN_HEAD_PATH . 'includes/class-mornrain-clean-head-assets.php';
		}

		/**
		 * Register WordPress hooks.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function hooks() {
			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_action( 'wp_loaded', array( $this, 'clean_up' ) );

			$emoji  = new Mornrain_Clean_Head_Emoji();
			$assets = new Mornrain_Clean_Head_Assets();

			$emoji->hooks();
			$assets->hooks();
		}

		/**
		 * Load translations.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			load_plugin_textdomain(
				'mornrain-clean-head',
				false,
				dirname( plugin_basename( MORNRAIN_CLEAN_HEAD_FILE ) ) . '/languages'
			);
		}

		/**
		 * Remove the selected wp_head callbacks.
		 *
		 * Runs on wp_loaded so that other plugins and themes have registered
		 * their own callbacks first.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function clean_up() {
			/**
			 * Filter whether the cleanup runs at all for this request.
			 *
			 * @since 1.0.0
			 * @param bool $run Whether to run the cleanup.
			 */
			if ( ! apply_filters( 'mornrain_clean_head_enabled', true ) ) {
				return;
			}

			if ( mornrain_clean_head_is_enabled( 'generator' ) ) {
				remove_action( 'wp_head', 'wp_generator' );
				remove_action( 'wp_head', 'wp_generator', 1 );
			}

			if ( mornrain_clean_head_is_enabled( 'rsd' ) ) {
				remove_action( 'wp_head', 'rsd_link' );
				remove_action( 'wp_head', 'wlwmanifest_link' );
			}

			if ( mornrain_clean_head_is_enabled( 'shortlink' ) ) {
				remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
				remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
			}

			if ( mornrain_clean_head_is_enabled( 'oembed' ) ) {
				remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
				remove_action( 'wp_head', 'wp_oembed_add_host_js', 10 );
			}

			if ( mornrain_clean_head_is_enabled( 'feed_links' ) ) {
				remove_action( 'wp_head', 'feed_links', 2 );
				remove_action( 'wp_head', 'feed_links_extra', 3 );
			}

			if ( mornrain_clean_head_is_enabled( 'adjacent_rel' ) ) {
				remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
			}
		}

		/**
		 * Seed the default switches on activation without clobbering saved ones.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function on_activate() {
			$stored = get_option( MORNRAIN_CLEAN_HEAD_OPTION, array() );

			if ( ! is_array( $stored ) ) {
				$stored = array();
			}

			update_option( MORNRAIN_CLEAN_HEAD_OPTION, array_merge( mornrain_clean_head_defaults(), $stored ) );
		}

		/**
		 * Remove the option row. Bound to register_uninstall_hook().
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function on_uninstall() {
			delete_option( MORNRAIN_CLEAN_HEAD_OPTION );

			if ( is_multisite() ) {
				$site_ids = get_sites( array( 'fields' => 'ids' ) );

				foreach ( $site_ids as $site_id ) {
					switch_to_blog( (int) $site_id );
					delete_option( MORNRAIN_CLEAN_HEAD_OPTION );
					restore_current_blog();
				}
			}
		}
	}
endif;
