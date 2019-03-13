<?php
namespace ElementorExtras\Modules\Map;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'map';
	}

	public function get_widgets() {
		return [
			'Google_Map',
		];
	}
}
