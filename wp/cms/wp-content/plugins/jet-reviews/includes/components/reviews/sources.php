<?php
namespace Jet_Reviews\Reviews;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Sources {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$_endpoints description]
	 * @var null
	 */
	private $registered_sources = array();

	/**
	 * Constructor for the class
	 */
	function __construct() {

		$this->load_files();

		$this->register_sources();
	}

	/**
	 * [load_files description]
	 * @return [type] [description]
	 */
	public function load_files() {
		require jet_reviews()->plugin_path( 'includes/components/reviews/sources/base.php' );
		require jet_reviews()->plugin_path( 'includes/components/reviews/sources/post.php' );
		require jet_reviews()->plugin_path( 'includes/components/reviews/sources/user.php' );
	}

	/**
	 * [register_sources description]
	 * @return [type] [description]
	 */
	public function register_sources() {
		$this->register_source( new Source\Post() );
		$this->register_source( new Source\User() );
	}

	/**
	 * [register_source description]
	 * @param  [type] $source_instance [description]
	 * @return [type]                  [description]
	 */
	public function register_source( $source_instance ) {

		if ( $source_instance ) {
			$this->registered_sources[ $source_instance->get_slug() ] = $source_instance;
		}
	}

	/**
	 * [register_source description]
	 * @param  [type] $source_instance [description]
	 * @return [type]                  [description]
	 */
	public function get_registered_sources() {
		return $this->registered_sources;
	}

	/**
	 * [get_register_source description]
	 * @param  [type] $source_slug [description]
	 * @return [type]              [description]
	 */
	public function get_registered_source( $source_slug ) {
		return isset( $this->registered_sources[ $source_slug ] ) ? $this->registered_sources[ $source_slug ] : false;
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}
