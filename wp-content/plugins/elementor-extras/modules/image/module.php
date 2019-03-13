<?php
namespace ElementorExtras\Modules\Image;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'image';
	}

	public function get_widgets() {
		return [
			'Random_Image',
			'Image_Comparison',
		];
	}
}
