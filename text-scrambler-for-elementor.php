<?php
/**
 * Plugin Name: Text Scrambler for Elementor
 * Description: This plugin adds a Text Scrambler widget to the Elementor Page Builder.
 * Plugin URI: https://wordpress.org/plugins/text-scrambler-for-elementor
 * Version: 1.0.0
 * Author: wepic
 * Author URI: https://wepic.be/
 * Text Domain: text-scrambler-for-elementor
 * 
 * Elementor tested up to: 3.2.5
 * Elementor Pro tested up to: 3.3.1
 */

final class TextScramblerForElementor
{
    const VERSION = '1.0.0';

    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    const MINIMUM_PHP_VERSION = '7.0';

    public function __construct()
    {
        add_action( 'init', array( $this, 'i18n' ) );

        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    public function i18n()
    {
        load_plugin_textdomain( 'text-scrambler-for-elementor' );
    }
    
    public function init()
    {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
            return;
        }

        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
            return;
        }

        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
            return;
        }

        require_once( 'plugin.php' );
    }

    public function admin_notice_missing_main_plugin()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'text-scrambler-for-elementor' ),
            '<strong>' . esc_html__( 'Text Scrambler for Elementor', 'text-scrambler-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'text-scrambler-for-elementor' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_elementor_version()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'text-scrambler-for-elementor' ),
            '<strong>' . esc_html__( 'Text Scrambler for Elementor', 'text-scrambler-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'text-scrambler-for-elementor' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_php_version()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'text-scrambler-for-elementor' ),
            '<strong>' . esc_html__( 'Text Scrambler for Elementor', 'text-scrambler-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'text-scrambler-for-elementor' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }
}

new TextScramblerForElementor();
