<?php
/**
 * Asset query string cleanup.
 *
 * Core, themes and plugins append a `ver` query argument to every stylesheet and
 * script URL. When that argument is not a content hash or a stable release tag
 * it busts the browser cache on every request. This module removes it, unless
 * the site owner opts out through the settings filter.
 *
 * @package Mornrain_Clean_Head
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mornrain_Clean_Head_Assets' ) ) :
	/**
	 * Strips the `ver` query argument from enqueued assets.
	 *
	 * @since 1.0.0
	 */
	final class Mornrain_Clean_Head_Assets {

		/**
		 * Register WordPress hooks.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function hooks() {
			if ( ! mornrain_clean_head_is_enabled( 'asset_versions' ) ) {
				return;
			}

			add_filter( 'style_loader_src', array( $this, 'strip_version_query' ), 15 );
			add_filter( 'script_loader_src', array( $this, 'strip_version_query' ), 15 );
		}

		/**
		 * Remove the `ver` argument from an asset URL.
		 *
		 * @since 1.0.0
		 * @param string|mixed $src Asset URL supplied by WordPress.
		 * @return string|mixed
		 */
		public function strip_version_query( $src ) {
			if ( ! is_string( $src ) || '' === $src ) {
				return $src;
			}

			/**
			 * Filter whether a specific URL keeps its version argument.
			 *
			 * @since 1.0.0
			 * @param bool   $strip Whether to strip the argument.
			 * @param string $src   The asset URL.
			 */
			if ( ! apply_filters( 'mornrain_clean_head_strip_asset_version', true, $src ) ) {
				return $src;
			}

			$parts = wp_parse_url( $src );

			if ( ! is_array( $parts ) || empty( $parts['query'] ) ) {
				return $src;
			}

			parse_str( (string) $parts['query'], $query );

			if ( ! isset( $query['ver'] ) ) {
				return $src;
			}

			unset( $query['ver'] );

			$rebuilt = '';

			if ( ! empty( $parts['scheme'] ) ) {
				$rebuilt .= $parts['scheme'] . '://';
			}

			if ( ! empty( $parts['host'] ) ) {
				$rebuilt .= $parts['host'];
			}

			if ( ! empty( $parts['port'] ) ) {
				$rebuilt .= ':' . (int) $parts['port'];
			}

			if ( ! empty( $parts['path'] ) ) {
				$rebuilt .= $parts['path'];
			}

			if ( ! empty( $query ) ) {
				$rebuilt .= '?' . http_build_query( $query, '', '&' );
			}

			if ( ! empty( $parts['fragment'] ) ) {
				$rebuilt .= '#' . $parts['fragment'];
			}

			return $rebuilt;
		}
	}
endif;
