<?php
/**
 * Register Event
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList;

defined( 'ABSPATH' ) || exit;

/**
 * SE_Register_Event Class.
 *
 * @class SimpleEventList
 *
 * @since 1.0.0
 */
class SE_Register_Event {

	/**
	 * SE_Register_Event Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_event' ) );
	}

	/**
	 * Register Event
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_event() {
		$labels = array(
			'name'                  => _x( 'Events', 'Post type general name', 'recipe' ),
			'singular_name'         => _x( 'Event', 'Post type singular name', 'recipe' ),
			'menu_name'             => _x( 'Simple Events', 'Admin Menu text', 'recipe' ),
			'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'recipe' ),
			'add_new'               => __( 'Add New', 'recipe' ),
			'add_new_item'          => __( 'Add New event', 'recipe' ),
			'new_item'              => __( 'New event', 'recipe' ),
			'edit_item'             => __( 'Edit event', 'recipe' ),
			'view_item'             => __( 'View event', 'recipe' ),
			'all_items'             => __( 'All events', 'recipe' ),
			'search_items'          => __( 'Search events', 'recipe' ),
			'parent_item_colon'     => __( 'Parent events', 'recipe' ),
			'not_found'             => __( 'No events found.', 'recipe' ),
			'not_found_in_trash'    => __( 'No events found in Trash.', 'recipe' ),
			'featured_image'        => _x( 'Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'recipe' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'recipe' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'recipe' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'recipe' ),
			'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'recipe' ),
			'insert_into_item'      => _x( 'Insert into event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'recipe' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'recipe' ),
			'filter_items_list'     => _x( 'Filter events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'recipe' ),
			'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'recipe' ),
			'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'recipe' ),
		);
		$args   = array(
			'labels'             => $labels,
			'description'        => 'Event custom post type.',
			'menu_icon'          => 'dashicons-calendar',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'simple-event' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
			'taxonomies'         => array( 'post_tag' ),
			'show_in_rest'       => true,
		);

		register_post_type( 'simple-events', $args );
	}
}
