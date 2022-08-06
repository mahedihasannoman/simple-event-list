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
 * Event Rest API class
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
		$this->namespace = simple_event_list()->slug . '/' . simple_event_list()->rest_version;
		$this->rest_base = '/events';
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
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Permission check. Public endpoint so true.
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
				),
				'about'     => array(
					'context'     => array( 'view' ),
					'description' => __( 'Description of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
				),
				'organizer' => array(
					'context'     => array( 'view' ),
					'description' => __( 'Organizer of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
				),
				'email'     => array(
					'context'     => array( 'view' ),
					'description' => __( 'Email of event organizer', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
				),
				'timestamp' => array(
					'context'     => array( 'view' ),
					'description' => __( 'Timestamp of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
				),
				'address'   => array(
					'context'     => array( 'view' ),
					'description' => __( 'Address of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
				),
				'latitude'  => array(
					'context'     => array( 'view' ),
					'description' => __( 'Latitude of event', 'simple-event-list' ),
					'type'        => 'number',
					'readonly'    => true,
				),
				'longitude' => array(
					'context'     => array( 'view' ),
					'description' => __( 'Longitude of event', 'simple-event-list' ),
					'type'        => 'number',
					'readonly'    => true,
				),
				'longitude' => array(
					'context'     => array( 'view' ),
					'description' => __( 'Longitude of event', 'simple-event-list' ),
					'type'        => 'string',
					'readonly'    => true,
				),
				'tags'      => array(
					'context'     => array( 'view' ),
					'description' => __( 'Tags of event', 'simple-event-list' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'string',
					),
					'readonly'    => true,
				),
			),
		);
		return $schema;
	}

	/**
	 * Retrieves the query params for collections.
	 *
	 * @since 1.0.0
	 *
	 * @return array Collection parameters.
	 */
	public function get_collection_params() {
		return array(
			'context' => $this->get_context_param( array( 'default' => 'view' ) ),
		);
	}

	/**
	 * Prepares a event object for serialization.
	 *
	 * @since 1.0.0
	 *
	 * @param array           $event   Event data.
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response Response object.
	 */
	public function prepare_item_for_response( $event, $request ) {
		$data   = array();
		$schema = $this->get_item_schema();

		if ( isset( $schema['properties']['id'] ) ) {
			$data['id'] = (int) $event['id'];
		}
		if ( isset( $schema['properties']['title'] ) ) {
			$data['title'] = $event['title'];
		}
		if ( isset( $schema['properties']['about'] ) ) {
			$data['about'] = $event['about'];
		}
		if ( isset( $schema['properties']['organizer'] ) ) {
			$data['organizer'] = $event['organizer'];
		}
		if ( isset( $schema['properties']['email'] ) ) {
			$data['email'] = $event['email'];
		}
		if ( isset( $schema['properties']['timestamp'] ) ) {
			$data['timestamp'] = $event['timestamp'];
		}
		if ( isset( $schema['properties']['address'] ) ) {
			$data['address'] = $event['address'];
		}
		if ( isset( $schema['properties']['latitude'] ) ) {
			$data['latitude'] = (float) $event['latitude'];
		}
		if ( isset( $schema['properties']['longitude'] ) ) {
			$data['longitude'] = (float) $event['longitude'];
		}
		if ( isset( $schema['properties']['tags'] ) ) {
			$data['tags'] = (array) $event['tags'];
		}

		$context = 'view';
		$data    = $this->add_additional_fields_to_object( $data, $request );
		$data    = $this->filter_response_by_context( $data, $context );

		// Wrap the data in a response object.
		$response = rest_ensure_response( $data );

		/**
		 * Filters a event returned from the REST API.
		 *
		 * Allows modification of the event data right before it is returned.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response  The response object.
		 * @param array            $event The original event array.
		 * @param WP_REST_Request  $request   Request used to generate the response.
		 */
		return apply_filters( 'rest_prepare_simple_event', $response, $event, $request );
	}

	/**
	 * Retrieve all events.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 * @api {GET} /wp-json/simple-event-list/v2/events
	 */
	public function get_items( $request ) {
		$data   = array();
		$events = Event::get_all();

		foreach ( $events as $event ) {
			$event  = $this->prepare_item_for_response( $event, $request );
			$data[] = $this->prepare_response_for_collection( $event );
		}

		$response = rest_ensure_response( $data );
		return $response;
	}
}
