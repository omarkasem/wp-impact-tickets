<?php
/**
 * Plugin Name: WP Vivid Seats Tickets
 * Description: Create tickets via vivid_seats.
 * Version:     1.0.2
 * Author:      Omar Kasem
 * License:     GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'OK_VIVID_SEATS_TICKETS_VERSION', '1.0.2' );
define( 'OK_VIVID_SEATS_TICKETS_NAME', 'wp-vivid-seats-tickets' );
define( 'OK_VIVID_SEATS_TICKETS_URL', plugin_dir_url( __FILE__ ) );
define( 'OK_VIVID_SEATS_TICKETS_PATH', plugin_dir_path( __FILE__ ) );


require plugin_dir_path( __FILE__ ) . 'app/App.php';

require plugin_dir_path( __FILE__ ) . 'app/Metabox.php';

require plugin_dir_path( __FILE__ ) . 'app/OptionPage.php';

require plugin_dir_path( __FILE__ ) . 'app/Shortcode.php';