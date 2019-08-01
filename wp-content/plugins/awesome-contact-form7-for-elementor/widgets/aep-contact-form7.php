<?php
/**
 * Awesome Contact form7 for Elementor
 *
 * @since 1.0.0
 */
namespace ACFE\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Bootstrap Elementor Pack alert widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class ACFE_Widget_Cf7 extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve alert widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'be-cf7';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve alert widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Contact Form7', 'aep' );
	}
	public function get_categories() {
		return [ 'basic' ];
	}
	/**
	 * Get widget icon.
	 *
	 * Retrieve alert widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-envelope-open-o';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'contact', 'form', 'contactForm7' ];
	}

	/**
	 * Register alert widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_cf7',
			[
				'label' => __( 'Contact Form7', 'aep' ),
			]
		);

		$this->add_control(
			'aep_cf7',
			[
				'label' => esc_html__( 'Select Contact Form', 'aep' ),
                'description' => esc_html__('Must need contact form7 installed & activate.','aep'),
				'type' => Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => aep_get_contact_form7(),
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'cf7_label_style',
			[
				'label' => __( 'Label Style', 'aep' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'cf7_label_align',
			[
				'label' => __( 'Alignment', 'aep' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'aep' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'aep' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'aep' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7-form label' => 'text-align: {{cf7_label_align}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => 'Label Typography',
				'name' => 'tile_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .wpcf7-form label,.wpcf7-form input::placeholder, .wpcf7-form textarea::placeholder ',
			]
		);
		$this->add_control(
			'cf_7_label_color',
			[
				'label' => __( 'Label Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7-form label' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'cf_7_label_space',
			[
				'label' => __( 'Label Spacing', 'aep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="text"], .wpcf7 input[type="email"], .wpcf7 textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'cf7_form_style',
			[
				'label' => __( 'Form Style', 'aep' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'wpcf7_form',
			[
				'label' => __( 'Alignment', 'aep' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'aep' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'aep' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'aep' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7' => 'float: {{wpcf7_form}};',
				],
			]
		);
		
		$this->add_control(
			'cf7_form_bg',
			[
				'label' => __( 'Form Background', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="text"], .wpcf7 input[type="email"], .wpcf7 textarea' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cf7_form_border',
				'selector' => '{{WRAPPER}} .wpcf7 input[type="text"], .wpcf7 input[type="email"], .wpcf7 textarea',
			]
		);
		$this->add_control(
			'cf7_form_radius',
			[
				'label' => __( 'Border Radius', 'aep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="text"], .wpcf7 input[type="email"], .wpcf7 textarea, .wpcf7 input[type="submit"], .wpcf7 textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'=>'after'
			]
		);
		$this->add_responsive_control(
  			'cf7_input_width',
  			[
  				'label' => __( 'Width', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%','px', 'em' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1200,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="text"], .wpcf7 input[type="email"], .wpcf7 textarea' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);  
		/*Textarea Height*/
        $this->add_responsive_control(
  			'cf7_input_height',
  			[
  				'label' => __( 'Input Height', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
					'em' => [
						'min' => 1,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="text"], .wpcf7 input[type="email"]' => 'height: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
		/*Textarea Height*/
        $this->add_responsive_control(
  			'cf7_textarea_height',
  			[
  				'label' => __( 'Textarea Height', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 300,
					],
					'em' => [
						'min' => 1,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpcf7 textarea' => 'height: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
        
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'cf7_button_style',
			[
				'label' => __( 'Button Style', 'aep' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'cf7_button_bg',
			[
				'label' => __( 'Button Background', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="submit"]' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'cf7_button_border',
			[
				'label' => __( 'Border Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="submit"]' => 'border:1px solid {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'cf7_button_text_color',
			[
				'label' => __( 'Button Text Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="submit"]' => 'color:{{VALUE}};',
				],
			]
		);
		
		 $this->add_responsive_control(
  			'cf7_button_width',
  			[
  				'label' => __( 'Width', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%','px', 'em',  ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1200,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} input.wpcf7-submit' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);  
        
        /*Button Height*/
        $this->add_responsive_control(
  			'cf7_button_height',
  			[
  				'label' => __( 'Height', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'em' => [
						'min' => 1,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} input.wpcf7-submit' => 'height: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
        
		$this->end_controls_section();

	}

	/**
	 * Render alert widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
	$settings = $this->get_settings_for_display();
	
	if(!empty($settings['aep_cf7'])){
    	   echo'<div class="elementor-shortcode aep-cf7 aep-cf7-'.$settings['aep_cf7'].'">';
                echo do_shortcode('[contact-form-7 id="'.$settings['aep_cf7'].'"]');    
           echo '</div>';  
    	}
	}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ACFE_Widget_Cf7() );
