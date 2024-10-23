<?php
/**
 * Plugin Name: Cleanup Unused Tags
 * Plugin URI:  https://github.com/vestrainteractive/cleanup-unused-tags/
 * Description: Deletes unused tags (tags with 0 posts) upon activation. 
 * Version:     1.0
 * Author:      Vestra Interactive
 * Author URI:  https://vestrainteractive.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/** NOTE:  THIS PLUGIN WILL LIKELY CRASH THE SITE UPON COMPLETION.  SIMPLY DELETE THE FOLDER TO RECTIFY */

// Only run on plugin activation
if ( ! defined( 'ABSPATH' ) || ! function_exists( 'activate_plugin' ) ) {
  exit;
}

register_activation_hook( __FILE__, 'cleanup_unused_tags' );

function cleanup_unused_tags() {
  global $wpdb;

  // Get unused tags (count = 0)
  $unused_tags = $wpdb->get_results( "SELECT t.term_id, t.name FROM {$wpdb->terms} AS t LEFT JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id WHERE tt.count = 0" );

  // Check if any unused tags found
  if ( $unused_tags ) {
    foreach ( $unused_tags as $tag ) {
      // Delete the tag
      wp_delete_term( $tag->term_id, 'post_tag' );
    }
    // Success message (optional)
    echo '<div class="notice notice-success"><p>Successfully deleted unused tags.</p></div>';
  } else {
    // No unused tags message (optional)
    echo '<div class="notice notice-info"><p>No unused tags found.</p></div>';
  }
}

// Include the GitHub Updater class
if ( file_exists( plugin_dir_path( __FILE__ ) . 'class-github-updater.php' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'class-github-updater.php';
}

// Initialize the updater
add_action( 'init', function() {
    new GitHub_Updater( 'cleanup-unused-tags', 'vestrainteractive/cleanup-unused-tags' ); // Replace with your plugin slug and folder name
});


?>
