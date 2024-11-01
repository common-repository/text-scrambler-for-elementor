<?php

namespace TextScramblerForElementor\Widgets;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit;

class TextScrambler extends Widget_Base
{
    public function get_name()
    {
        return 'text-scrambler-for-elementor';
    }
    
    public function get_title()
    {
        return __( 'Text Scrambler', 'text-scrambler-for-elementor' );
    }

    public function get_icon()
    {
        return 'tsfe-icon';
    }
    
    public function get_categories()
    {
        return [ 'general' ];
    }

    public function get_style_depends()
    {
        return [ 'text-scrambler-style' ];
    }

    public function get_script_depends()
    {
        return [ 'text-scrambler-script' ];
    }
    
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_scrambler',
            [
                'label' => __( 'Scrambler', 'text-scrambler-for-elementor' ),
            ],
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'text',
            [
                'label' => __( 'Text', 'text-scrambler-for-elementor' ),
                'type' =>  \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ],
        );

        $repeater->add_control(
            'delay',
            [
                'label' => __( 'Delay (ms)', 'text-scrambler-for-elementor' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5000,
                'step' => 1,
            ],
        );
        
        $this->add_control(
            'items',
            [
                'label' => __( 'Items', 'text-scrambler-for-elementor' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'text' => __( 'Item #1', 'text-scrambler-for-elementor' ),
                        'wait' => 1000,
                    ],
                    [
                        'text' => __( 'Item #2', 'text-scrambler-for-elementor' ),
                        'wait' => 1000,
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ],
        );

        $this->add_control(
            'initial_text',
            [
                'label' => __( 'Initial text', 'text-scrambler-for-elementor' ),
                'type' =>  \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
            ],
        );

        $this->add_control(
            'flip',
            [
                'label' => __( 'Character switches', 'text-scrambler-for-elementor' ),
                'description' => __( 'The number of character switches.', 'text-scrambler-for-elementor' ),
                'type' =>  \Elementor\Controls_Manager::NUMBER,
                'min' => 10,
                'max' => 100,
                'step' => 10,
                'default' => 10,
            ],
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Loop', 'text-scrambler-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'text-scrambler-for-elementor' ),
                'label_off' => __( 'No', 'text-scrambler-for-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ],
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __( 'Content', 'text-scrambler-for-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __( 'Typography', 'text-scrambler-for-elementor' ),
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .tsfe-container',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'text-scrambler-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tsfe-container' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'text-scrambler-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'text-scrambler-for-elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tsfe-container' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => __( 'Align', 'text-scrambler-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __( 'Left', 'text-scrambler-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'text-scrambler-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'text-scrambler-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tsfe-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_active_settings();

        $uniqid = uniqid();

        $this->add_render_attribute( 'container', 'id', 'tsfe-' . $uniqid );

        echo '<div class="tsfe-container" ' . wp_kses_post( $this->get_render_attribute_string( 'container' ) ) . '>';
        echo esc_attr( $this->get_render_attribute_string( 'container' ) );
        echo esc_html( $settings['initial_text'] );
        echo '</div>';

        echo '<script>';
        echo 'window.addEventListener("load", () => {';
        echo 'function Scrambler_' . esc_js( $uniqid ) . '() {';
        echo 'Scramble.select("#tsfe-' . esc_js( $uniqid ) . '")';
        echo '.setConfig({flip: ' . esc_js( (int) $settings['flip'] ) . ', interval: 100})';

        $delay = 0;
        
        foreach ( $settings['items'] as $item ) {
            if ( $item['delay'] > 0 ) {
                echo '.wait(' . esc_js( (int) $item['delay'] ) . ')';

                $delay += (int) $item['delay'];
            }

            echo '.setText("' . esc_js( $item['text'] ) . '")';
            echo '.descramble()';
        }

        echo ';';
        echo '}';
        echo 'Scrambler_' . esc_js( $uniqid ) . '();';

        if ( $settings['loop'] === 'yes' ) {
            echo 'setInterval(() => {';
            echo 'Scrambler_' . esc_js( $uniqid ) . '();';
            echo '}, ' . esc_js( ( ( ( (int) $settings['flip'] * 100 ) * count( $settings['items'] ) ) + $delay ) + ( count( $settings['items'] ) * 100 ) ) . ');';
        }

        echo '});';
        echo '</script>';
    }
}
