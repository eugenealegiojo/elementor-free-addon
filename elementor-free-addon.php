<?php
/**
 * Plugin Name: Elementor Free Addon
 * Description: Free addons everyone.
 * Plugin URI: #
 * Version: 0.1.0
 * Author: Eugene Alegiojo
 * Text Domain: ea-elementor-addon
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'plugins_loaded', function () {
	require_once __DIR__ . '/vendor/autoload.php';

	\EA\ElementorFreeAddon\Plugin::instance();
});
