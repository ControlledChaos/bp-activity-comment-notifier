<?php

/**
 * Plugin Name: BuddyPress Activity Comment Notifier
 * Plugin URI: https://buddydev.com/plugins/buddypress-activity-comment-notifier/
 * Author: BuddyDev
 * Author URI: https://buddydev.com/
 * Description: Show facebook like notification in the notification drop down when some user comment on your update or on other users update where you have commented
 * Version: 1.2.0
 * License: GPL
 * Compatible with BuddyPress 2.7+
 */

/**
 * Special disclaimer: BuddyPress does not allows multiple items from same component with similar action(It is grouped by buddypress before delivering to the notfication formatting function), so I have hacked the component_action to be unique per item, because we want update for individual action items
 * Most important: It is just for fun :) hope you would like it
 */

// Do not allow direct access over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}
// component slug, invisible.
define( 'BP_ACTIVITY_NOTIFIER_SLUG', 'ac_notification' );

/**
 * Register a dummy notifier component, I don't want to do it,
 * but bp has no other mechanism for passing the notification data to function,
 * so we need the format_notification_function
 */
function ac_notifier_setup_globals() {

	if ( ! bp_is_active( 'activity' ) ) {
		// no need to register it.
		return;
	}

	$bp = buddypress();

	$bp->ac_notifier                        = new stdClass();
	// I assume others are not going to use this.
	$bp->ac_notifier->id                    = 'ac_notifier';
	$bp->ac_notifier->slug                  = BP_ACTIVITY_NOTIFIER_SLUG;
	// show the notification.
	$bp->ac_notifier->notification_callback = 'ac_notifier_format_notifications';

	$bp->active_components[ $bp->ac_notifier->id ] = $bp->ac_notifier->id;

	do_action( 'ac_notifier_setup_globals' );
}

add_action( 'bp_setup_globals', 'ac_notifier_setup_globals' );

/**
 * Load required files when bp is loaded
 */
function ac_notifier_load() {

	$path = plugin_dir_path( __FILE__ );

	$files = array(
		'core/functions.php',
		'core/actions.php',
	);

	foreach ( $files as $file ) {
		require_once $path . $file;
	}

}

add_action( 'bp_loaded', 'ac_notifier_load' );

/**
 * Load translations.
 */
function ac_notifier_load_textdomain() {
	load_plugin_textdomain( 'bp-activity-comment-notifier', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'bp_init', 'ac_notifier_load_textdomain' );
