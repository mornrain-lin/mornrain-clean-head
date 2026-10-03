<?php
/**
 * Settings helpers for MornRain Clean Head.
 *
 * @package Mornrain_Clean_Head
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mornrain_clean_head_defaults' ) ) :
	/**
	 * Default switches.
	 *
	 * @since 1.0.0
	 * @return array<string, int>
	 */
	function mornrain_clean_head_defaults() {
		return array(
			'emoji'            => 1,
			'generator'        => 1,
			'rsd'              => 1,
			'shortlink'        => 1,
			'oembed'           => 1,
			'feed_links'       => 0,
			'adjacent_rel'     => 0,
			'asset_versions'   => 1,
		);
	}
endif;

if ( ! function_exists( 'mornrain_clean_head_get_settings' ) ) :
	/**
	 * Read the switches merged over the defaults.
	 *
	 * @since 1.0.0
	 * @return array<string, int>
	 */
	function mornrain_clean_head_get_settings() {
		$stored = get_option( MORNRAIN_CLEAN_HEAD_OPTION, array() );

		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		$settings = array();

		foreach ( mornrain_clean_head_defaults() as $key => $default ) {
			$settings[ $key ] = isset( $stored[ $key ] ) ? ( empty( $stored[ $key ] ) ? 0 : 1 ) : $default;
		}

		/**
		 * Filter the effective switches.
		 *
		 * @since 1.0.0
		 * @param array<string, int> $settings Effective switches.
		 */
		return (array) apply_filters( 'mornrain_clean_head_settings', $settings );
	}
endif;

if ( ! function_exists( 'mornrain_clean_head_is_enabled' ) ) :
	/**
	 * Whether a single switch is on.
	 *
	 * @since 1.0.0
	 * @param string $key Switch name.
	 * @return bool
	 */
	function mornrain_clean_head_is_enabled( $key ) {
		$settings = mornrain_clean_head_get_settings();
		$key      = sanitize_key( $key );

		return ! empty( $settings[ $key ] );
	}
endif;

if ( ! function_exists( 'mornrain_clean_head_sanitize' ) ) :
	/**
	 * Sanitise a settings array coming from a form or the REST API.
	 *
	 * @since 1.0.0
	 * @param mixed $input Raw input.
	 * @return array<string, int>
	 */
	function mornrain_clean_head_sanitize( $input ) {
		$clean = array();

		if ( ! is_array( $input ) ) {
			return mornrain_clean_head_defaults();
		}

		foreach ( mornrain_clean_head_defaults() as $key => $default ) {
			$clean[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
		}

		return $clean;
	}
endif;
