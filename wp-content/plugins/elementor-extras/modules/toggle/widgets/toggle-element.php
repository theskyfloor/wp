<?php
namespace ElementorExtras\Modules\Toggle\Widgets;

// Elementor Extras Classes
use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Modules\Toggle\Skins;
use ElementorExtras\Modules\TemplatesControl\Module as TemplatesControl;
use ElementorExtras\Group_Control_Transition;

// Elementor Classes
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Toggle_Element
 *
 * @since 2.0.0
 */
class Toggle_Element extends Extras_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'ee-toggle-element';
	}

	public function get_title() {
		return __( 'Toggle Element', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-toggle';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [
			'toggle-element',
			'gsap-js',
			'jquery-resize-ee',
		];
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
	}

	protected function _register_controls() {
		$this->register_content_controls();
	}

	protected function register_content_controls() {

		$this->start_controls_section(
			'section_elements',
			[
				'label' => __( 'Elements', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'elements_repeater' );

			$repeater->start_controls_tab( 'element_content', [ 'label' => __( 'Content', 'elementor-extras' ) ] );

				$repeater->add_control(
					'content_type',
					[
						'label'		=> __( 'Type', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'text',
						'options' 	=> [
							'text' 		=> __( 'Text', 'elementor-extras' ),
							'template' 	=> __( 'Template', 'elementor-extras' ),
						],
					]
				);

				$repeater->add_control(
					'content',
					[
						'label' 	=> __( 'Content', 'elementor-extras' ),
						'type' 		=> Controls_Manager::WYSIWYG,
						'dynamic'	=> [ 'active' => true ],
						'default' 	=> __( 'I am the content ready to be toggled', 'elementor-extras' ),
						'condition'	=> [
							'content_type' => 'text',
						],
					]
				);

				TemplatesControl::add_controls( $repeater, [
					'condition' => [
						'content_type' => 'template',
					],
					'prefix' => 'content_',
				] );

			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'element_label', [ 'label' => __( 'Label', 'elementor-extras' ) ] );

				$repeater->add_control(
					'text',
					[
						'default'	=> '',
						'type'		=> Controls_Manager::TEXT,
						'dynamic'	=> [ 'active' => true ],
						'label' 	=> __( 'Text', 'elementor-extras' ),
						'separator' => 'none',
					]
				);

				$repeater->add_control(
					'icon',
					[
						'label' 		=> __( 'Icon', 'elementor-extras' ),
						'type' 			=> Controls_Manager::ICON,
						'label_block' 	=> false,
						'default' 		=> '',
					]
				);

				$repeater->add_control(
					'icon_align',
					[
						'label' 	=> __( 'Icon Position', 'elementor-extras' ),
						'label_block' => false,
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'left',
						'options' 	=> [
							'left' 		=> __( 'Before', 'elementor-extras' ),
							'right' 	=> __( 'After', 'elementor-extras' ),
						],
						'condition' => [
							'icon!' => '',
						],
					]
				);

				$repeater->add_control(
					'icon_indent',
					[
						'label' 	=> __( 'Icon Spacing', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'range' 	=> [
							'px' 	=> [
								'max' => 50,
							],
						],
						'condition' => [
							'icon!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-icon--right' => 'margin-left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-icon--left' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$repeater->add_control(
					'text_color',
					[
						'label' 	=> __( 'Text Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}}.ee-toggle-element__controls__item' => 'color: {{VALUE}};',
						],
					]
				);

				$repeater->add_control(
					'text_active_color',
					[
						'label' 	=> __( 'Active Text Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}}.ee-toggle-element__controls__item.ee--is-active,
							 {{WRAPPER}} {{CURRENT_ITEM}}.ee-toggle-element__controls__item.ee--is-active:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$repeater->add_control(
					'active_color',
					[
						'label' 	=> __( 'Indicator Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
					]
				);

			$repeater->end_controls_tab();

			$repeater->end_controls_tabs();

			$this->add_control(
				'elements',
				[
					'label' 	=> __( 'Elements', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'text' 	=> '',
							'content' => __( 'I am the content ready to be toggled', 'elementor-extras' ),
						],
						[
							'text' 	=> '',
							'content' => __( 'I am the content of another element ready to be toggled', 'elementor-extras' ),
						],
					],
					'fields' 		=> array_values( $repeater->get_controls() ),
					'title_field' 	=> '{{{ text }}}',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle',
			[
				'label' => __( 'Toggle', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'toggle_position',
				[
					'label'		=> __( 'Position', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'before',
					'options' 	=> [
						'before'  	=> __( 'Before', 'elementor-extras' ),
						'after' 	=> __( 'After', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'indicator_speed',
				[
					'label' 	=> __( 'Indicator Speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0.1,
							'max' 	=> 2,
							'step'	=> 0.1,
						],
					],
					'default' 	=> [
						'size' => 0.3
					],
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_toggler',
			[
				'label' => __( 'Toggler', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'toggle_style',
				[
					'label'		=> __( 'Style', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'round',
					'options' 	=> [
						'round'  => __( 'Round', 'elementor-extras' ),
						'square' => __( 'Square', 'elementor-extras' ),
					],
					'prefix_class' => 'ee-toggle-element--',
				]
			);

			$this->add_responsive_control(
				'toggle_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'label_block'	=> false,
					'type' 			=> Controls_Manager::CHOOSE,
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
					'default' 	=> 'center',
					'selectors' => [
						'{{WRAPPER}} .ee-toggle-element__toggle' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'toggle_zoom',
				[
					'label' 	=> __( 'Zoom', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 16,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 28,
							'min' 	=> 12,
							'step' 	=> 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-toggle-element__controls-wrapper' => 'font-size: {{SIZE}}px;',
					],
				]
			);

			$this->add_control(
				'toggle_spacing',
				[
					'label' 	=> __( 'Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 24,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 100,
							'min' 	=> 0,
							'step' 	=> 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-toggle-element__controls-wrapper--before' => 'margin-bottom: {{SIZE}}px;',
						'{{WRAPPER}} .ee-toggle-element__controls-wrapper--after' => 'margin-top: {{SIZE}}px;',
					],
				]
			);

			$this->add_control(
				'toggle_padding',
				[
					'label' 	=> __( 'Padding', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 6,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 10,
							'min' 	=> 0,
							'step' 	=> 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-toggle-element__indicator' => 'margin: {{SIZE}}px;',
						'{{WRAPPER}} .ee-toggle-element__controls-wrapper' => 'padding: {{SIZE}}px;',
					],
				]
			);

			$this->add_responsive_control(
				'toggle_width',
				[
					'label' 	=> __( 'Width (%)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 100,
							'min' 	=> 0,
							'step' 	=> 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-toggle-element__controls-wrapper' => 'width: {{SIZE}}%;',
					],
				]
			);

			$this->add_responsive_control(
				'toggle_radius',
				[
					'label' 	=> __( 'Radius', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 4,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 10,
							'min' 	=> 0,
							'step' 	=> 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}}.ee-toggle-element--square .ee-toggle-element__controls-wrapper' => 'border-radius: {{SIZE}}px;',
						'{{WRAPPER}}.ee-toggle-element--square .ee-toggle-element__indicator' => 'border-radius: calc( {{SIZE}}px - 2px );',
					],
					'condition' => [
						'toggle_style' => 'square',
					]
				]
			);

			$this->add_control(
				'toggle_background',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-toggle-element__controls-wrapper' => 'background-color: {{VALUE}};'
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'toggle',
					'selector' 	=> '{{WRAPPER}} .ee-toggle-element__controls-wrapper',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_indicator',
			[
				'label' => __( 'Indicator', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'indicator_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'frontend_available' => true,
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'indicator',
					'selector' 	=> '{{WRAPPER}} .ee-toggle-element__indicator',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => __( 'Labels', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'labels_info',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'After adjusting some of these settings, interact with the toggler so that the position of the indicator is updated. ', 'elementor-extras' ),
					'content_classes' 	=> 'ee-raw-html ee-raw-html__info',
				]
			);

			$this->add_control(
				'labels_stack',
				[
					'label'		=> __( 'Stack On', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' 	=> [
						''  		=> __( 'None', 'elementor-extras' ),
						'desktop'  	=> __( 'All', 'elementor-extras' ),
						'tablet'  	=> __( 'Tablet & Mobile', 'elementor-extras' ),
						'mobile' 	=> __( 'Mobile', 'elementor-extras' ),
					],
					'prefix_class' => 'ee-toggle-element--stack-',
				]
			);

			$this->add_responsive_control(
				'labels_align',
				[
					'label' 		=> __( 'Align Labels', 'elementor-extras' ),
					'description' 	=> __( 'Label alignment only works if you set a custom width for the toggler.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'options' 		=> [
						'flex-start'    => [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'flex-end' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'stretch' 		=> [
							'title' 	=> __( 'Justify', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'default' 	=> 'center',
					'selectors' => [
						'{{WRAPPER}} .ee-toggle-element__controls' => 'align-items: {{VALUE}}; justify-content: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'text_align',
				[
					'label' 		=> __( 'Align Label Text', 'elementor-extras' ),
					'description' 	=> __( 'Label text alignment only works if your labels have text.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'flex-start'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'flex-end' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-toggle-element__controls__item' => 'justify-content: {{VALUE}};',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'labels_typography',
					'selector' 	=> '{{WRAPPER}} .ee-toggle-element__controls__item',
					'exclude'	=> ['font_size'],
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'labels',
					'selector' 		=> '{{WRAPPER}} .ee-toggle-element__controls__item',
				]
			);

			$this->start_controls_tabs( 'labels_style' );

			$this->start_controls_tab( 'labels_style_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'labels_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-toggle-element__controls__item' => 'color: {{VALUE}};'
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'labels_style_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'labels_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-toggle-element__controls__item:hover' => 'color: {{VALUE}};'
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'labels_style_active', [ 'label' => __( 'Active', 'elementor-extras' ) ] );

				$this->add_control(
					'labels_color_active',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-toggle-element__controls__item.ee--is-active,
							 {{WRAPPER}} .ee-toggle-element__controls__item.ee--is-active:hover' => 'color: {{VALUE}};'
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( [
			'wrapper' => [
				'class' => [
					'ee-toggle-element',
				],
			],
			'toggle' => [
				'class' => [
					'ee-toggle-element__toggle',
				],
			],
			'controls-wrapper' => [
				'class' => [
					'ee-toggle-element__controls-wrapper',
					'ee-toggle-element__controls-wrapper--' . $settings['toggle_position'],
				],
			],
			'indicator' => [
				'class' => [
					'ee-toggle-element__indicator',
				],
				'data-default-color' => $settings['indicator_color'],
			],
			'controls' => [
				'class' => [
					'ee-toggle-element__controls',
				],
			],
			'elements' => [
				'class' => [
					'ee-toggle-element__elements',
				],
			],
		] );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'toggle' ); ?>>
				<?php if ( 'before' === $settings['toggle_position'] ) $this->render_toggle(); ?>
				<div <?php echo $this->get_render_attribute_string( 'elements' ); ?>>
					<?php foreach ( $settings['elements'] as $index => $item ) {

						$element_key = $this->get_repeater_setting_key( 'element', 'elements', $index );

						$this->add_render_attribute( $element_key, [
							'class' => [
								'ee-toggle-element__element',
								'elementor-repeater-item-' . $item['_id'],
							]
						] );

						?><div <?php echo $this->get_render_attribute_string( $element_key ); ?>>
							<?php if ( 'text' === $item['content_type'] ) {
								$this->render_text( $index, $item );
							} else if ( 'template' === $item['content_type'] ) {
								$this->render_template( $index, $item );
							} ?>
						</div><?php
					} ?>
				</div>
				<?php if ( 'after' === $settings['toggle_position'] ) $this->render_toggle(); ?>
			</div>
		</div>
		<?php

	}

	public function render_toggle() {
		$settings = $this->get_settings_for_display();

		?><div <?php echo $this->get_render_attribute_string( 'controls-wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'indicator' ); ?>></div>

			<?php if ( $settings['elements'] ) { ?>
			<ul <?php echo $this->get_render_attribute_string( 'controls' ); ?>>
				<?php foreach ( $settings['elements'] as $index => $item ) {

				$icon_key = $this->get_repeater_setting_key( 'icon', 'elements', $index );
				$control_key = $this->get_repeater_setting_key( 'control', 'elements', $index );
				$control_text_key = $this->get_repeater_setting_key( 'control-text', 'elements', $index );

				$_has_icon = false;

				$this->add_render_attribute( [
					$control_key => [
						'class' => [
							'ee-toggle-element__controls__item',
							'elementor-repeater-item-' . $item['_id'],
						]
					],
					$control_text_key => [
						'class' => 'ee-toggle-element__controls__text',
						'unselectable' => 'on',
					],
				] );

				if ( '' !== $item['active_color'] ) {
					$this->add_render_attribute( $control_key, 'data-color', $item['active_color'] );
				}

				if ( ! empty( $item['icon'] ) ) {
					$this->add_render_attribute( $icon_key, 'class', [
						'ee-toggle-element__controls__icon',
						'ee-icon',
						'ee-icon--' . $item['icon_align'],
					] );

					if ( '' === $item['text'] ) {
						$this->add_render_attribute( $icon_key, 'class', [
							'ee-icon--flush',
						] );
					}

					$_has_icon = true;
				}

				if ( '' === $item['text'] ) {
					$this->add_render_attribute( $control_key, 'class', 'ee--is-empty' );
				}

				?><li <?php echo $this->get_render_attribute_string( $control_key ); ?>>
					<?php if ( $_has_icon ) : ?>
						<span <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
							<i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
						</span>
					<?php endif; ?>

					<?php if ( '' === $item['text'] && ! $_has_icon ) { ?>
					<span <?php echo $this->get_render_attribute_string( $control_text_key ); ?>>
					<?php } ?>

						<?php if( '' !== $item['text'] ) { echo $item['text']; } else if ( ! $_has_icon ) { echo '&nbsp;'; } ?>

					<?php if ( '' === $item['text'] && ! $_has_icon ) { ?>
						</span>
					<?php } ?>
				</li><?php } ?>
			</ul>
			<?php } ?>
		</div><?php
	}

	public function render_text( $index, $item ) {
		echo $this->parse_text_editor( $item['content'] );
	}

	public function render_template( $index, $item ) {

		$template_id = $item[ 'content_' . $item['content_template_type'] . '_template_id'];

		TemplatesControl::render_template_content( $template_id );
	}

	public function _content_template() {}

}