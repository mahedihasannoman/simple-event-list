<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Events REST API
 *
 * @since 1.0.0
 *
 * @package SimpleEventList\REST\V2
 */

namespace SimpleEventList\REST\V2;

use SimpleEventList\Model\Event;

defined( 'ABSPATH' ) || exit;

/**
 * Class Event Rest API
 *
 * @since 1.0.0
 */
class Events extends \WP_REST_Controller {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->namespace = $GLOBALS['simple_event_list']->slug . '/v2';
		$this->rest_base = '/events/';
		$this->register_routes();
	}

	/**
	 * Register the route
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);
	}

	/**
	 * Permission check.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return bool
	 */
	public function get_items_permissions_check( $request ) {
		return true;
	}

	/**
	 * Get the event schema
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'Events',
			'type'       => 'object',
			'properties' => array(
				'id'        => array(
					'context'     => array( 'view' ),
					'description' => __( 'A unique numeric ID for the event.', 'simple-event-list' ),
					'readonly'    => true,
					'type'        => 'integer',
				),
				'title'     => array(
					'context'     => array( 'view' ),
					'description' => __( 'Event title', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'about'     => array(
					'context'     => array( 'view' ),
					'description' => __( 'Description of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => null,
						// Note: sanitization implemented in self::prepare_item_for_response().
					),
				),
				'organizer' => array(
					'context'     => array( 'view' ),
					'description' => __( 'Organizer of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'email'     => array(
					'context'     => array( 'view' ),
					'description' => __( 'Email of event organizer', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_email',
					),
				),
				'timestamp' => array(
					'context'     => array( 'view' ),
					'description' => __( 'Timestamp of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'address'   => array(
					'context'     => array( 'view' ),
					'description' => __( 'Address of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'latitude'  => array(
					'context'     => array( 'view' ),
					'description' => __( 'Latitude of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'longitude' => array(
					'context'     => array( 'view' ),
					'description' => __( 'Longitude of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'longitude' => array(
					'context'     => array( 'view' ),
					'description' => __( 'Longitude of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'tags'      => array(
					'context'     => array( 'view' ),
					'description' => __( 'Tags of event', 'simple-event-list' ),
					'type'        => 'array',
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => null,
						// Note: sanitization implemented in self::prepare_item_for_response().
					),
				),
			),
		);
		return $schema;
	}

	/**
	 * Retrieve miusages data.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response | WP_Error
	 * @api {GET} /wp-json/simple-event-list/v2/events/ Get events data
	 */
	public function get_items( $request ) {
		$retval   = Event::get_all();
		$response = rest_ensure_response( $retval );
		return $response;
	}
}
