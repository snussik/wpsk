<?php
namespace Jet_Engine\Modules\Rest_API_Listings;

class Module {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    object
	 */
	private static $instance = null;

	public $slug = 'rest-api-listings';
	public $data;
	public $request;
	public $settings;

	private $notices = array();

	/**
	 * Constructor for the class
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Init module components
	 *
	 * @return [type] [description]
	 */
	public function init() {

		require_once $this->module_path( 'auth-types/manager.php' );
		require_once $this->module_path( 'listings/manager.php' );
		require_once $this->module_path( 'data.php' );
		require_once $this->module_path( 'request.php' );
		require_once $this->module_path( 'settings.php' );
		require_once $this->module_path( 'forms.php' );

		$this->data       = new Data( $this );
		$this->request    = new Request();
		$this->settings   = new Settings();
		$this->listings   = new Listings\Manager();
		$this->auth_types = new Auth_Types\Manager();
		$this->form       = new Forms();

	}

	/**
	 * Return path inside module
	 *
	 * @param  string $relative_path [description]
	 * @return [type]                [description]
	 */
	public function module_path( $relative_path = '' ) {
		return jet_engine()->modules->modules_path( $this->slug . '/inc/' . $relative_path );
	}

	/**
	 * Return url inside module
	 *
	 * @param  string $relative_path [description]
	 * @return [type]                [description]
	 */
	public function module_url( $relative_path = '' ) {
		return jet_engine()->plugin_url( 'includes/modules/' . $this->slug . '/inc/' . $relative_path );
	}

	/**
	 * Add notice to stack
	 *
	 * @param string $type    [description]
	 * @param [type] $message [description]
	 */
	public function add_notice( $type = 'error', $message ) {
		$this->notices[] = array(
			'type'    => $type,
			'message' => $message,
		);
	}

	/**
	 * Add notice to stack
	 *
	 * @param string $type    [description]
	 * @param [type] $message [description]
	 */
	public function get_notices() {
		return $this->notices;
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}
