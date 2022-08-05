<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * SimpleEvents Post type Register
 *
 * @package SimpleEventList\PostTypes
 * @since 1.0.0
 */

namespace SimpleEventList\PostTypes;

defined( 'ABSPATH' ) || exit;

/**
 * SimpleEvent Custom post type Class.
 *
 * @class SimpleEvent
 *
 * @since 1.0.0
 */
class SimpleEvent extends RegisterPostType {

	/**
	 * Post Type
	 *
	 * @var string
	 */
	protected const POST_TYPE = 'simple-event';

	/**
	 * Taxonomy
	 *
	 * @var string
	 */
	protected const TAXONOMY = 'event-tag';

	/**
	 * SimpleEvents Constructor.
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
			'name'                  => _x( 'Events', 'Post type general name', 'simple-event-list' ),
			'singular_name'         => _x( 'Event', 'Post type singular name', 'simple-event-list' ),
			'menu_name'             => _x( 'Simple Events', 'Admin Menu text', 'simple-event-list' ),
			'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'simple-event-list' ),
			'add_new'               => __( 'Add New', 'simple-event-list' ),
			'add_new_item'          => __( 'Add New event', 'simple-event-list' ),
			'new_item'              => __( 'New event', 'simple-event-list' ),
			'edit_item'             => __( 'Edit event', 'simple-event-list' ),
			'view_item'             => __( 'View event', 'simple-event-list' ),
			'all_items'             => __( 'All events', 'simple-event-list' ),
			'search_items'          => __( 'Search events', 'simple-event-list' ),
			'parent_item_colon'     => __( 'Parent events', 'simple-event-list' ),
			'not_found'             => __( 'No events found.', 'simple-event-list' ),
			'not_found_in_trash'    => __( 'No events found in Trash.', 'simple-event-list' ),
			'featured_image'        => _x( 'Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'simple-event-list' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'simple-event-list' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'simple-event-list' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'simple-event-list' ),
			'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'simple-event-list' ),
			'insert_into_item'      => _x( 'Insert into event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'simple-event-list' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'simple-event-list' ),
			'filter_items_list'     => _x( 'Filter events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'simple-event-list' ),
			'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'simple-event-list' ),
			'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'simple-event-list' ),
		);
		$args   = array(
			'labels'             => $labels,
			'description'        => 'Event custom post type.',
			'menu_icon'          => 'dashicons-calendar',
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'author' ),
			'show_in_rest'       => true,
			'publicly_queryable' => false,
		);

		$this->register_post_type( $args );

		// Regisgter taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => _x( 'Event Tags', 'taxonomy general name', 'simple-event-list' ),
			'singular_name'              => _x( 'Tag', 'taxonomy singular name', 'simple-event-list' ),
			'search_items'               => __( 'Search Tags', 'simple-event-list' ),
			'popular_items'              => __( 'Popular Tags', 'simple-event-list' ),
			'all_items'                  => __( 'All Tags', 'simple-event-list' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag', 'simple-event-list' ),
			'update_item'                => __( 'Update Tag', 'simple-event-list' ),
			'add_new_item'               => __( 'Add New Tag', 'simple-event-list' ),
			'new_item_name'              => __( 'New Tag Name', 'simple-event-list' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'simple-event-list' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'simple-event-list' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'simple-event-list' ),
			'not_found'                  => __( 'No tags found.', 'simple-event-list' ),
			'menu_name'                  => __( 'Tags', 'simple-event-list' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'publicly_queryable'    => false,
			'show_in_rest'          => true,

		);

		$this->register_taxonomy( $args );
	}

	/**
	 * Get the post type
	 *
	 * @since 1.0.0
	 *
	 * @static
	 *
	 * @return string
	 */
	public static function post_type() {
		return static::POST_TYPE;
	}

	/**
	 * Get the taxonomy
	 *
	 * @since 1.0.0
	 *
	 * @static
	 *
	 * @return string
	 */
	public static function taxonomy() {
		return static::TAXONOMY;
	}

}
