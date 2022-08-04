<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Custom Post type Register class
 *
 * @package SimpleEventList\PostTypes
 * @since 1.0.0
 */

namespace SimpleEventList\PostTypes;

defined( 'ABSPATH' ) || exit;

/**
 * Register Custom post type Class.
 *
 * @class RegisterPostType
 *
 * @since 1.0.0
 */
abstract class RegisterPostType {

	/**
	 * Post Type
	 *
	 * @var string
	 */
	protected const POST_TYPE = '';

	/**
	 * Taxonomy
	 *
	 * @var string
	 */
	protected const TAXONOMY = '';

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
		if ( '' !== static::POST_TYPE ) {
			register_post_type( static::POST_TYPE, $args );
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
		if ( '' !== static::POST_TYPE && '' !== static::TAXONOMY ) {
			register_taxonomy( static::TAXONOMY, static::POST_TYPE, $args );
		}
	}
}
