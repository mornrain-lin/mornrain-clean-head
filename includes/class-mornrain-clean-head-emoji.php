<?php
/**
 * Emoji cleanup.
 *
 * WordPress prints an inline emoji detection script plus a stylesheet on every
 * front-end and admin request. On modern browsers this is dead weight, so the
 * plugin detaches both and keeps emoji out of feeds and mail as well.
 *
 * @package Mornrain_Clean_Head
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mornrain_Clean_Head_Emoji' ) ) :
	/**
	 * Detaches the core emoji callbacks.
	 *
	 * @since 1.0.0
	 */
	final class Mornrain_Clean_Head_Emoji {

		/**
		 * Register WordPress hooks.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function hooks() {
			if ( ! mornrain_clean_head_is_enabled( 'emoji' ) ) {
				return;
			}

			add_action( 'init', array( $this, 'detach' ) );
			add_filter( 'tiny_mce_plugins', array( $this, 'filter_tinymce_plugins' ) );
			add_filter( 'emoji_svg_url', '__return_false' );
		}

		/**
		 * Detach every core emoji callback.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function detach() {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );

			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		}

		/**
		 * Drop the wpemoji TinyMCE plugin.
		 *
		 * @since 1.0.0
		 * @param array<int, string>|mixed $plugins Registered TinyMCE plugins.
		 * @return array<int, string>
		 */
		public function filter_tinymce_plugins( $plugins ) {
			if ( ! is_array( $plugins ) ) {
				return array();
			}

			return array_values( array_diff( $plugins, array( 'wpemoji' ) ) );
		}
	}
endif;
