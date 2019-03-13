<?php
namespace ElementorPro\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementorPro\Modules\QueryControl\Module;
use ElementorPro\Modules\Woocommerce\Classes\Products_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Products extends Products {

	public function get_name() {
		return 'woocommerce-archive-products';
	}

	public function get_title() {
		return __( 'Archive Products', 'elementor-pro' );
	}

	public function get_categories() {
		return [
			'woocommerce-elements-archive',
		];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'rows',
			[
				'default' => 4,
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'paginate',
			[
				'default' => 'yes',
			]
		);

		$this->update_control(
			'section_query',
			[
				'type' => 'hidden',
			]
		);

		$this->update_control(
			'query_post_type',
			[
				'default' => 'current_query',
			]
		);
	}
}
