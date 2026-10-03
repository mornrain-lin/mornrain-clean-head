<?php
/**
 * Uninstall routine.
 *
 * Deletes the single option row the plugin creates, on every site of a
 * multisite network.
 *
 * @package Mornrain_Clean_Head
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$mornrain_clean_head_option = 'mornrain_clean_head_settings';

delete_option( $mornrain_clean_head_option );

if ( is_multisite() ) {
	$mornrain_clean_head_sites = get_sites( array( 'fields' => 'ids' ) );

	foreach ( $mornrain_clean_head_sites as $mornrain_clean_head_site ) {
		switch_to_blog( (int) $mornrain_clean_head_site );
		delete_option( $mornrain_clean_head_option );
		restore_current_blog();
	}
}
