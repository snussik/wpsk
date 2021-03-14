<?php
namespace Jet_CW\Settings;

use Jet_Dashboard\Base\Page_Module as Page_Module_Base;
use Jet_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Compare extends Page_Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return string
	 */
	public function get_page_slug() {
		return 'jet-cw-compare-settings';
	}

	/**
	 * Returns parent slug
	 *
	 * @return string
	 */
	public function get_parent_slug() {
		return 'settings-page';
	}

	/**
	 * Returns page name
	 *
	 * @return string
	 */
	public function get_page_name() {
		return esc_html__( 'Compare Settings', 'jet-cw' );
	}

	/**
	 * Returns category name
	 *
	 * @return string
	 */
	public function get_category() {
		return 'jet-cw-settings';
	}

	/**
	 * Returns page link
	 *
	 * @return string
	 */
	public function get_page_link() {
		return Dashboard::get_instance()->get_dashboard_page_url( $this->get_parent_slug(), $this->get_page_slug() );
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_style(
			'jet-cw-admin-css',
			jet_cw()->plugin_url( 'assets/css/jet-cw-admin.css' ),
			false,
			jet_cw()->get_version()
		);

		wp_enqueue_script(
			'jet-cw-admin-vue-components',
			jet_cw()->plugin_url( 'assets/js/admin-vue-components.min.js' ),
			array( 'cx-vue-ui' ),
			jet_cw()->get_version(),
			true
		);

		wp_localize_script(
			'jet-cw-admin-vue-components',
			'jetCWSettingsConfig',
			apply_filters( 'jet-cw/admin/settings-page/localized-config', jet_cw()->settings->get_localize_data() )
		);

	}

	/**
	 * License page config
	 *
	 * @param array $config
	 * @param bool  $page
	 * @param bool  $subpage
	 *
	 * @return array
	 */
	public function page_config( $config = array(), $page = false, $subpage = false ) {

		$config['pageModule'] = $this->get_parent_slug();
		$config['subPageModule'] = $this->get_page_slug();

		return $config;

	}

	/**
	 * Add page templates
	 *
	 * @param array $templates
	 * @param bool  $page
	 * @param bool  $subpage
	 *
	 * @return array
	 */
	public function page_templates( $templates = array(), $page = false, $subpage = false ) {

		$templates['jet-cw-compare-settings'] = jet_cw()->plugin_path( 'templates/admin-templates/compare-settings.php' );

		return $templates;
	}
}
