<?php
namespace Creativefilterable\PageSettings;

use Elementor\Controls_Manager;
use Elementor\Core\DocumentTypes\PageBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Page_Settings {

	const PANEL_TAB = 'new-tab';

	public function __construct() {
		add_action( 'elementor/init', [ $this, 'bwdfg_filterable_add_panel_tab' ] );
		add_action( 'elementor/documents/register_controls', [ $this, 'bwdfg_filterable_register_document_controls' ] );
	}

	public function bwdfg_filterable_add_panel_tab() {
		Controls_Manager::add_tab( self::PANEL_TAB, esc_html__( 'New Filterable Gallery', 'bwdfg-filterable-gallery' ) );
	}

	public function bwdfg_filterable_register_document_controls( $document ) {
		if ( ! $document instanceof PageBase || ! $document::get_property( 'has_elements' ) ) {
			return;
		}

		$document->start_controls_section(
			'bwdfg_filterable_new_section',
			[
				'label' => esc_html__( 'Settings', 'bwdfg-filterable-gallery' ),
				'tab' => self::PANEL_TAB,
			]
		);

		$document->add_control(
			'bwdfg_filterable_text',
			[
				'label' => esc_html__( 'Title', 'bwdfg-filterable-gallery' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'bwdfg-filterable-gallery' ),
			]
		);

		$document->end_controls_section();
	}
}
