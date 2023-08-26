<?php
/**
 * Plugin Name: BWD Filterable Gallery
 * Description: BWD Filterable Gallery plugin with 30+ types of Filterable Gallery also responsive gallery for Elementor.
 * Plugin URI:  https://bwdplugins.com/bwd-filterable-gallery
 * Version:     1.0
 * Author:      Best WP Developer
 * Author URI:  https://bestwpdeveloper.com/
 * Text Domain: bwdfg-filterable-gallery
 * Elementor tested up to: 3.0.0
 * Elementor Pro tested up to: 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once ( plugin_dir_path(__FILE__) ) . '/includes/class-tgm-plugin-activation.php';
final class FinalBWDFGFilterable{

	const VERSION = '1.0';

	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	const MINIMUM_PHP_VERSION = '7.0';

	public function __construct() {
		// Load translation
		add_action( 'bwdfg_init', array( $this, 'bwdfg_loaded_textdomain' ) );
		// bwdfg_init Plugin
		add_action( 'plugins_loaded', array( $this, 'bwdfg_init' ) );
	}

	public function bwdfg_loaded_textdomain() {
		load_plugin_textdomain( 'bwdfg-filterable-gallery' );
	}

	public function bwdfg_init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			// For tgm plugin activation
			add_action( 'tgmpa_register', [$this, 'bwdfg_filterable_register_required_plugins'] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'bwdfg_admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'bwdfg_admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'bwdfg_boots.php' );
	}

	function bwdfg_filterable_register_required_plugins() {
		$plugins = array(
			array(
				'name'        => esc_html__('Elementor', 'bwdfg-filterable-gallery'),
				'slug'        => 'elementor',
				'is_callable' => 'wpseo_init',
			),
		);

		$config = array(
			'id'           => 'bwdfg-filterable-gallery',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'manage_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		);
	
		tgmpa( $plugins, $config );
	}

	public function bwdfg_admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'bwdfg-filterable-gallery' ),
			'<strong>' . esc_html__( 'BWD Filterable Gallery', 'bwdfg-filterable-gallery' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'bwdfg-filterable-gallery' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>' . esc_html__('%1$s', 'bwdfg-filterable-gallery') . '</p></div>', $message );
	}

	public function bwdfg_admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'bwdfg-filterable-gallery' ),
			'<strong>' . esc_html__( 'BWD Filterable Gallery', 'bwdfg-filterable-gallery' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'bwdfg-filterable-gallery' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>' . esc_html__('%1$s', 'bwdfg-filterable-gallery') . '</p></div>', $message );
	}
}

// Instantiate bwdfg-filterable-gallery.
new FinalBWDFGFilterable();
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );