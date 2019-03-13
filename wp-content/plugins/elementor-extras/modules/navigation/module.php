<?php
namespace ElementorExtras\Modules\Navigation;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'navigation';
	}

	public function get_widgets() {
		return [
			'Offcanvas',
			'Slide_Menu',
		];
	}

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	public static function get_templates() {
		return self::elementor()->templates_manager->get_source( 'local' )->get_items();
	}

	public static function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . __( 'You Haven’t Saved Templates Yet.', 'elementor-pro' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . __( 'Want to learn more about Elementor library?', 'elementor-pro' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . __( 'Click Here', 'elementor-extras' ) . '</a>
				</div>
				</div>';
	}

	public function get_animation_options() {
		return [
			'' 				=> __( 'None', 'elementor-extras' ),
			'zoom-in' 		=> __( 'Zoom In', 'elementor-extras' ),
			'zoom-out' 		=> __( 'Zoom Out', 'elementor-extras' ),
			'slide-right' 	=> __( 'Slide Right', 'elementor-extras' ),
			'slide-left' 	=> __( 'Slide Left', 'elementor-extras' ),
			'slide-top' 	=> __( 'Slide Top', 'elementor-extras' ),
			'slide-bottom' 	=> __( 'Slide Bottom', 'elementor-extras' ),
			'unfold-horizontal' => __( 'Unfold Horizontal', 'elementor-extras' ),
			'unfold-vertical' => __( 'Unfold Vertical', 'elementor-extras' ),
		];
	}
}
