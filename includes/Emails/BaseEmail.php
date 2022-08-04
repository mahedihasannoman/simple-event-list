<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Setup for base email class
 *
 * @package SimpleEventList\Emails
 * @since 1.0.0
 */

namespace SimpleEventList\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Base Email Class.
 *
 * @class BaseEmail
 *
 * @since 1.0.0
 */
abstract class BaseEmail {

	/**
	 * Email to address
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract protected function to();

	/**
	 * Content of the email
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract protected function message();

	/**
	 * Subject of the email
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract protected function subject();

	/**
	 * Email headers
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function headers() {
		return array( 'Content-Type: text/html; charset=UTF-8' );
	}

	/**
	 * Send the email
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the email was sent successfully.
	 */
	public function send() {
		return wp_mail( $this->to(), $this->subject(), $this->message(), $this->headers() );
	}
}
