<?php

/**
 * Plugin Name: Kebbet plugins - Custom Post Type: Work
 * Plugin URI: https://github.com/kebbet/kebbet-cpt-work
 * Description: Registers a Custom Post Type.
 * Version: 20210527.01
 * Author: Erik Betshammar
 * Author URI: https://verkan.se
 *
 * @package kebbet-cpt-work
 */

namespace kebbet\cpt\work;

const POSTTYPE  = 'work';
const SLUG      = 'works';
const IS_PUBLIC = false;
const ICON      = 'admin-customizer';
const MENUPOS   = 9;
const THUMBNAIL = true;

/**
 * Link to ICONS
 *
 * @link https://developer.wordpress.org/resource/dashicons/
 */

/**
 * Hook into the 'init' action
 */
function init() {
	load_textdomain();
	register();
	if ( true === THUMBNAIL ) {
		add_theme_support( 'post-thumbnails' );
	}
}
add_action( 'init', __NAMESPACE__ . '\init', 0 );

/**
 * Flush rewrite rules on registration.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_post_type
 */
function rewrite_flush() {
	// First, we "add" the custom post type via the above written function.
	// Note: "add" is written with quotes, as CPTs don't get added to the DB,
	// They are only referenced in the post_type column with a post entry,
	// when you add a post of this CPT.
	register();

	// ATTENTION: This is *only* done during plugin activation hook in this example!
	// You should *NEVER EVER* do this on every page load!!
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\rewrite_flush' );

/**
 * Load plugin textdomain.
 */
function load_textdomain() {
	load_plugin_textdomain( 'kebbet-cpt-work', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Register Custom Post Type
 */
function register() {

	$labels_args = array(
		'name'                     => _x( 'Works', 'Post Type General Name', 'kebbet-cpt-work' ),
		'singular_name'            => _x( 'Work', 'Post Type Singular Name', 'kebbet-cpt-work' ),
		'menu_name'                => __( 'Works', 'kebbet-cpt-work' ),
		'name_admin_bar'           => __( 'Work-post', 'kebbet-cpt-work' ),
		'parent_item_colon'        => __( 'Parent post:', 'kebbet-cpt-work' ),
		'all_items'                => __( 'All posts', 'kebbet-cpt-work' ),
		'add_new_item'             => __( 'Add new', 'kebbet-cpt-work' ),
		'add_new'                  => __( 'Add new post', 'kebbet-cpt-work' ),
		'new_item'                 => __( 'New post', 'kebbet-cpt-work' ),
		'edit_item'                => __( 'Edit post', 'kebbet-cpt-work' ),
		'update_item'              => __( 'Update post', 'kebbet-cpt-work' ),
		'view_item'                => __( 'View post', 'kebbet-cpt-work' ),
		'view_items'               => __( 'View posts', 'kebbet-cpt-work' ),
		'search_items'             => __( 'Search posts', 'kebbet-cpt-work' ),
		'not_found'                => __( 'Not found', 'kebbet-cpt-work' ),
		'not_found_in_trash'       => __( 'No posts found in Trash', 'kebbet-cpt-work' ),
		'featured_image'           => __( 'Artwork image', 'kebbet-cpt-work' ),
		'set_featured_image'       => __( 'Set artwork image', 'kebbet-cpt-work' ),
		'remove_featured_image'    => __( 'Remove artwork image', 'kebbet-cpt-work' ),
		'use_featured_image'       => __( 'Use as artwork image', 'kebbet-cpt-work' ),
		'insert_into_item'         => __( 'Insert into item', 'kebbet-cpt-work' ),
		'uploaded_to_this_item'    => __( 'Uploaded to this post', 'kebbet-cpt-work' ),
		'items_list'               => __( 'Items list', 'kebbet-cpt-work' ),
		'items_list_navigation'    => __( 'Items list navigation', 'kebbet-cpt-work' ),
		'filter_items_list'        => __( 'Filter items list', 'kebbet-cpt-work' ),
		'archives'                 => __( 'Work-posts archive', 'kebbet-cpt-work' ),
		'attributes'               => __( 'Work-post attributes', 'kebbet-cpt-work' ),
		'item_published'           => __( 'Post published', 'kebbet-cpt-work' ),
		'item_published_privately' => __( 'Post published privately', 'kebbet-cpt-work' ),
		'item_reverted_to_draft'   => __( 'Post reverted to Draft', 'kebbet-cpt-work' ),
		'item_scheduled'           => __( 'Post scheduled', 'kebbet-cpt-work' ),
		'item_updated'             => __( 'Post updated', 'kebbet-cpt-work' ),
		// 5.7 + 5.8
		'filter_by_date'           => __( 'Filter posts by date', 'kebbet-cpt-work' ),
		'item_link'                => __( 'Work post link', 'kebbet-cpt-work' ),
		'item_link_description'    => __( 'A link to a work post', 'kebbet-cpt-work' ),
	);

	$supports_args = array(
		'title',
		'page-attributes',
	);

	if ( true === THUMBNAIL ) {
		$supports_args = array_merge( $supports_args, array( 'thumbnail' ) );
	}

	$rewrite_args      = array(
		'slug'       => SLUG,
		'with_front' => false,
		'pages'      => false,
		'feeds'      => true,
	);
	$capabilities_args = \kebbet\cpt\work\roles\capabilities();
	$post_type_args    = array(
		'label'               => __( 'Work post type', 'kebbet-cpt-work' ),
		'description'         => __( 'Custom post type for artistic work', 'kebbet-cpt-work' ),
		'labels'              => $labels_args,
		'supports'            => $supports_args,
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => IS_PUBLIC,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => MENUPOS,
		'menu_icon'           => 'dashicons-' . ICON,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => IS_PUBLIC,
		'exclude_from_search' => false,
		'publicly_queryable'  => IS_PUBLIC,
		'rewrite'             => $rewrite_args,
		'capabilities'        => $capabilities_args,
		// Adding map_meta_cap will map the meta correctly.
		'show_in_rest'        => true,
		'map_meta_cap'        => true,
	);
	register_post_type( POSTTYPE, $post_type_args );
}

/**
 * Add the content to the `At a glance`-widget.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/at-a-glance.php';

/**
 * Adds and modifies the admin columns for the post type.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-columns.php';

/**
 * Adds admin messages for the post type.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-messages.php';

/**
 * Adjust roles and capabilities for post type
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/roles.php';
