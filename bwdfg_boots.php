<?php
namespace Creativefilterable;

use Creativefilterable\PageSettings\Page_Settings;
define( "BWDFG_ASFSK_ASSETS_PUBLIC_DIR_FILE", plugin_dir_url( __FILE__ ) . "assets/public" );
define( "BWDFG_ASFSK_ASSETS_ADMIN_DIR_FILE", plugin_dir_url( __FILE__ ) . "assets/admin" );
class ClassBWDFGfilterable {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function bwdfg_admin_editor_scripts() {
		add_filter( 'script_loader_tag', [ $this, 'bwdfg_admin_editor_scripts_as_a_module' ], 10, 2 );
	}

	public function bwdfg_admin_editor_scripts_as_a_module( $tag, $handle ) {
		if ( 'bwdfg_the_filterable_editor' === $handle ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}

		return $tag;
	}

	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/bwdfg-filterable.php' );
	}

	public function bwdfg_register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\BWDFGfilterable() );
	}

	private function add_page_settings_controls() {
		require_once( __DIR__ . '/page-settings/creative-filterable-manager.php' );
		new Page_Settings();
	}

	// Register Category
	function bwdfg_add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'bwdfg-filterable-gallery-category',
			[
				'title' => esc_html__( 'BWD Filterable Gallery', 'bwdfg-filterable-gallery' ),
				'icon' => 'eicon-person',
			]
		);
	}
	public function bwdfg_all_assets_for_the_public(){
		$all_css_js_file = array(
            'bwdfg_filterable_bootstrap_css' => array('bwdfg_path_define'=>BWDFG_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/css/bootstrap.min.css'),
            'bwdfg_filterable_font_awesome_css' => array('bwdfg_path_define'=>BWDFG_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/css/plugins/font-awesome/css/all.min.css'),
            'bwdfg_filterable_style_css' => array('bwdfg_path_define'=>BWDFG_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/css/style.css'),

            'bwdfg_filterable_bootstrap_js' => array('bwdfg_path_define'=>BWDFG_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/js/bootstrap.bundle.min.js'),
            'bwdfg_filterable_main_js' => array('bwdfg_path_define'=>BWDFG_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/js/main.js'),
        );
        foreach($all_css_js_file as $handle => $fileinfo){
            wp_enqueue_style( $handle, $fileinfo['bwdfg_path_define'], null, '1.0', 'all');
            wp_enqueue_script( $handle, $fileinfo['bwdfg_path_define'], ['jquery'], '1.0', true);
        }
	}
	public function bwdfg_all_assets_for_elementor_editor_admin(){
		$all_css_js_file = array(
            'bwdfg_filterable_admin_icon_css' => array('bwdfg_path_admin_define'=>BWDFG_ASFSK_ASSETS_ADMIN_DIR_FILE . '/icon.css'),
        );
        foreach($all_css_js_file as $handle => $fileinfo){
            wp_enqueue_style( $handle, $fileinfo['bwdfg_path_admin_define'], null, '1.0', 'all');
        }
	}

	public function __construct() {
		// For public assets
		add_action('wp_enqueue_scripts', [$this, 'bwdfg_all_assets_for_the_public']);

		// For Elementor Editor
		add_action('elementor/editor/before_enqueue_scripts', [$this, 'bwdfg_all_assets_for_elementor_editor_admin']);
		
		// Register Category
		add_action( 'elementor/elements/categories_registered', [ $this, 'bwdfg_add_elementor_widget_categories' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'bwdfg_register_widgets' ] );

		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'bwdfg_admin_editor_scripts' ] );
		
		$this->add_page_settings_controls();
	}
}

// Instantiate Plugin Class
ClassBWDFGfilterable::instance();