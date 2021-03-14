<?php
namespace Jet_Reviews\Reviews\Source;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

abstract class Base {

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	abstract public function get_slug();

	/**
	 * [get_name description]
	 * @return [type] [description]
	 */
	abstract public function get_name();

	/**
	 * [get_source_id description]
	 * @return [type] [description]
	 */
	abstract public function get_source_current_id();

	/**
	 * [get_source_settings description]
	 * @return [type] [description]
	 */
	abstract public function get_source_settings();

}
