<?php
namespace Jet_Reviews\Settings;

use Jet_Reviews\Admin as Admin;
use Jet_Reviews\Endpoints as Endpoints;
use Jet_Reviews\Reviews\Data as Reviews_Data;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$key description]
	 * @var string
	 */
	private $slug = 'settings-manager';

	/**
	 * [$key description]
	 * @var string
	 */
	public $key = 'jet-reviews-settings';

	/**
	 * [$settings description]
	 * @var null
	 */
	public $settings_page_config = null;

	/**
	 * [$page description]
	 * @var null
	 */
	public $page = null;

	/**
	 * [$subpage_modules description]
	 * @var array
	 */
	public $subpage_modules = array();

	/**
	 * Constructor for the class
	 */
	function __construct() {

		$this->load_files();

		add_action( 'jet-reviews/rest/init-endpoints', array( $this, 'init_endpoints' ), 10, 1 );

		$this->subpage_modules = apply_filters( 'jet-reviews/settings/registered-subpage-modules', array(
			'jet-reviews-post-types' => array(
				'class' => '\\Jet_Reviews\\Settings\\Post_Types',
				'args'  => array(),
			),
			'jet-reviews-integrations' => array(
				'class' => '\\Jet_Reviews\\Settings\\Integrations',
				'args'  => array(),
			),
		) );

		if ( is_admin() ) {
			add_action( 'init', array( $this, 'register_settings_category' ), 10 );

			add_action( 'init', array( $this, 'init_plugin_subpage_modules' ), 10 );
		}

		add_action( 'admin_menu', array(  $this, 'register_page' ), 12 );
	}

	/**
	 * [load_files description]
	 * @return [type] [description]
	 */
	public function load_files() {
		require jet_reviews()->plugin_path( 'includes/components/settings/rest-api/save-settings.php' );
		require jet_reviews()->plugin_path( 'includes/components/settings/rest-api/sync-rating-data.php' );
	}

	/**
	 * Register add/edit page
	 *
	 * @return void
	 */
	public function register_page() {
		add_submenu_page(
			Admin::get_instance()->admin_page_slug,
			__( 'Settings', 'jet-reviews' ),
			__( 'Settings', 'jet-reviews' ),
			'manage_options',
			add_query_arg(
				array(
					'page'    => 'jet-dashboard-settings-page',
					'subpage' => 'jet-reviews-post-types'
				),
				admin_url( 'admin.php' )
			)
		);
	}

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function register_settings_category() {

		\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_module_category( array(
			'name'     => esc_html__( 'JetReviews', 'jet-reviews' ),
			'slug'     => 'jet-reviews-settings',
			'priority' => 1
		) );
	}

	/**
	 * [init_plugin_subpage_modules description]
	 * @return [type] [description]
	 */
	public function init_plugin_subpage_modules() {
		require jet_reviews()->plugin_path( 'includes/components/settings/admin-pages/post-types-submodule.php' );
		require jet_reviews()->plugin_path( 'includes/components/settings/admin-pages/integrations-submodule.php' );

		foreach ( $this->subpage_modules as $subpage => $subpage_data ) {
			\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_subpage_module( $subpage, $subpage_data );
		}
	}

	/**
	 * [init_endpoints description]
	 * @return [type] [description]
	 */
	public function init_endpoints( $rest_api_manager ) {
		$rest_api_manager->register_endpoint( new Endpoints\Save_Settings() );
		$rest_api_manager->register_endpoint( new Endpoints\Sync_Rating_Data() );
	}

	/**
	 * [get description]
	 * @param  [type]  $setting [description]
	 * @param  boolean $default [description]
	 * @return [type]           [description]
	 */
	public function get( $setting, $default = false ) {

		if ( null === $this->settings_page_config ) {
			$this->settings_page_config = get_option( $this->key, array() );
		}

		return isset( $this->settings_page_config[ $setting ] ) ? $this->settings_page_config[ $setting ] : $default;
	}

	/**
	 * [get_post_type_data description]
	 * @param  [type] $post_type [description]
	 * @return [type]            [description]
	 */
	public function get_post_type_data( $post_type ) {

		$post_types = jet_reviews_tools()->get_post_types();

		$option_name = $post_type . '-type-settings';

		$allowed_post_types = $this->get( 'allowed-post-types', array( 'post' => 'true' ) );

		$default_allowed = isset( $allowed_post_types[ $post_type ] ) ? filter_var( $allowed_post_types[ $post_type ], FILTER_VALIDATE_BOOLEAN ) : false;

		$default_data = array(
			'allowed'               => $default_allowed,
			'name'                  => isset( $post_types[ $post_type ] ) ? $post_types[ $post_type ] : $post_type,
			'slug'                  => $post_type,
			'review_type'           => 'default',
			'allowed_roles'         => array(
				'administrator',
				'editor',
				'author',
				'contributor',
				'subscriber',
			),
			'verifications'         => array(),
			'comment_verifications' => array(),
			'need_approve'          => false,
			'comments_allowed'      => true,
			'comments_need_approve' => false,
			'approval_allowed'      => true,
			'metadata'              => false,
			'metadata_rating_key'   => '_jet_reviews_average_rating',
			'metadata_ratio_bound'  => 5,
		);

		$saved_data = $this->get( $option_name, array() );

		return wp_parse_args( $saved_data, $default_data );

	}

	/**
	 * [get_the_post_type_data description]
	 * @return [type] [description]
	 */
	public function get_the_post_type_data() {
		$post_id = get_the_ID();
		$post_type = get_post_type( $post_id );

		return $this->get_post_type_data( $post_type );
	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function settings_page_config() {
		$post_types = jet_reviews_tools()->get_post_types();

		$plugin_settings_data = array(
			'captcha' => $this->get( 'captcha', array(
				'enable'     => false,
				'site_key'   => '',
				'secret_key' => '',
			) ),
		);

		$avaliable_post_types_data = array();
		$avaliable_post_types_options = array();
		$avaliable_review_types = Reviews_Data::get_instance()->get_review_types_list();

		foreach ( $post_types as $slug => $name ) {
			$review_post_type_option = $slug . '-type-settings';

			$plugin_settings_data[ $review_post_type_option ] = jet_reviews()->settings->get_post_type_data( $slug );

			$avaliable_post_types_options[] = array(
				'label' => $name,
				'value' => $slug,
			);
		}

		$config = array(
			'saveSettingsRoute'    => '/jet-reviews-api/v1/save-settings',
			'syncRatingDataRoute'  => '/jet-reviews-api/v1/sync-rating-data',
			'avaliablePostTypes'   => $avaliable_post_types_options,
			'avaliableReviewTypes' => $avaliable_review_types,
			'allRolesOptions'      => jet_reviews_tools()->get_roles_options(),
			'verificationOptions'  => jet_reviews()->user_manager->get_verification_options(),
			'settingsData'         => $plugin_settings_data,
		);

		return $config;
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
