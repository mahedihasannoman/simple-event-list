<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * SampleEvents Post type Register
 *
 * @package SimpleEventList
 * @since 1.0.0
 */

namespace SimpleEventList\PostTypes;

defined( 'ABSPATH' ) || exit;

/**
 * SampleEvents Custom post type Class.
 *
 * @class SampleEvents
 *
 * @since 1.0.0
 */
abstract class RegisterPostType {

	/**
	 * Post Type
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Taxonomy
	 *
	 * @var string
	 */
	protected $taxonomy;

	/**
	 * Register post type
	 *
	 * @param array $args Array or string of arguments for registering a post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function register_post_type( $args ) {
		if ( '' !== $this->post_type ) {
			register_post_type( $this->post_type, $args );
		}
	}

	/**
	 * Register taxonomy
	 *
	 * @param array $args Array or query string of arguments for registering a taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function register_taxonomy( $args ) {
		if ( '' !== $this->post_type && '' !== $this->taxonomy ) {
			register_taxonomy( $this->taxonomy, $this->post_type, $args );
		}
	}
}
