<?php
namespace ElementorExtras\Modules\Toggle;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'toggle';
	}

	public function get_widgets() {
		return [
			'Toggle_Element',
		];
	}
}
