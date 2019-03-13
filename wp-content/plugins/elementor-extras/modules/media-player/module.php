<?php
namespace ElementorExtras\Modules\MediaPlayer;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'media-player';
	}

	public function get_widgets() {
		return [
			'HTML5_Video',
			'Audio_Player',
		];
	}
}
