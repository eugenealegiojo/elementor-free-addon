<?php

namespace EA\ElementorFreeAddon;

/**
 * Main plugin class.
 *
 * @since 0.1.0
 */
final class Plugin {

	/**
	 * Minimum Elementor Version
	 *
	 * @since 0.1.0
	 * @var string Minimum Elementor version required to run the addon.
	 */
	private const MINIMUM_ELEMENTOR_VERSION = '3.20.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 0.1.0
	 * @var string Minimum PHP version required to run the addon.
	 */
	private const MINIMUM_PHP_VERSION = '9'; //'7.4';

	/**
	 * Class instance
	 *
	 * @since 0.1.0
	 * @static
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 0.1.0
	 * @static
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->define_constants();

		register_activation_hook( EA_ELEMENTOR_ADDON_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( EA_ELEMENTOR_ADDON_FILE, [ $this, 'deactivate' ] );

		$this->check_compatibility();

		// Load the core files.
		$this->load();
	}

	public function define_constants() {
		define( 'EA_ELEMENTOR_ADDON_FILE', trailingslashit( dirname( __DIR__, 1 ) ) . 'elementor-free-addon.php' );
		define( 'EA_ELEMENTOR_ADDON_DIR', trailingslashit( plugin_dir_path( EA_ELEMENTOR_ADDON_FILE ) ) );
		define( 'EA_ELEMENTOR_ADDON_URL', trailingslashit( plugin_dir_url( __DIR__ ) ) );
		define( 'EA_ELEMENTOR_ADDON_ASSETS_URL', EA_ELEMENTOR_ADDON_URL . 'assets' );
		define( 'EA_ELEMENTOR_ADDON_BUILD_DIR', EA_ELEMENTOR_ADDON_DIR . 'build' );
		define( 'EA_ELEMENTOR_ADDON_BUILD_URL', EA_ELEMENTOR_ADDON_URL . 'build' );
		define( 'EA_ELEMENTOR_ADDON_VERSION', '1.0.0' );
	}

	/**
	 * Initialize plugin
	 */
	public function load() {
		// Maybe to late for this since we already have localization added during compatibility check.
		load_plugin_textdomain( 'ea-elementor-addon' );
	}

	/**
	 * Compatibility Checks
	 *
	 * Checks whether the site meets the addon requirement.
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function check_compatibility() {

		// Check if Elementor is installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return false;
		}
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ea-elementor-addon' ),
			'<strong>' . esc_html__( 'Elementor Free Addon', 'ea-elementor-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ea-elementor-addon' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ea-elementor-addon' ),
			'<strong>' . esc_html__( 'Elementor Free Addon', 'ea-elementor-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ea-elementor-addon' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ea-elementor-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Addon', 'ea-elementor-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ea-elementor-addon' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function activate() {
		// Do something during activation.
	}

	public function deactivate() {
		// Do something during deactivation.
	}
}
