<?php
/**
 * Mixtape_Expect
 *
 * Asserts about invariants
 *
 * @package Mixtape
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Mixtape_Expect
 */
class WP_Job_Manager_REST_Expect {
	/**
	 * Expect a certain class
	 *
	 * @param mixed  $thing The thing to test.
	 * @param string $class_name The class.
	 *
	 * @throws WP_Job_Manager_REST_Exception Fail if we got an unexpected class.
	 */
	static function is_a( $thing, $class_name ) {
		self::is_object( $thing );
		self::that( is_a( $thing, $class_name ), 'Expected ' . $class_name . ', got ' . get_class( $thing ) );
	}

	/**
	 * Expect that something implements an interface.
	 *
	 * @param object|string|mixed $thing The thing to check.
	 * @param string              $interface_name The interface name.
	 */
	static function implements_interface( $thing, $interface_name ) {
		$thing_class = is_object( $thing ) ? get_class( $thing ) : (string) $thing;
		$interfaces = class_implements( $thing );
		self::that( in_array( $interface_name, $interfaces, true ), 'Class ' . $thing_class . ' does not implement interface ' . $interface_name );
	}

	/**
	 * Expect that thing is an object
	 *
	 * @param mixed $thing The thing.
	 * @throws WP_Job_Manager_REST_Exception Throw if not an object.
	 */
	static function is_object( $thing ) {
		self::that( is_object( $thing ), 'Variable is is not an Object' );
	}

	/**
	 * Express an invariant.
	 *
	 * @param bool   $cond The boolean condition that needs to hold.
	 * @param string $fail_message In case of failure, the reason this failed.
	 *
	 * @throws WP_Job_Manager_REST_Exception Fail if condition doesn't hold.
	 */
	static function that( $cond, $fail_message ) {
		if ( ! $cond ) {
			throw new WP_Job_Manager_REST_Exception( $fail_message );
		}
	}

	/**
	 * This method should be Overridden
	 *
	 * @param string $method The method name.
	 *
	 * @throws WP_Job_Manager_REST_Exception To Overrride this.
	 */
	static function should_override( $method ) {
		throw new WP_Job_Manager_REST_Exception( $method . ' should be overriden' );
	}
}
