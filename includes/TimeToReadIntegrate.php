<?php
/**
 * Make calculations for reading count
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadIntegrate') ) {
  class TimeToReadIntegrate {

    /**
     * The single instance of the class.
     * 
     * @since 1.0.0
     */
	protected static $_instance = null;

    /**
     * Constructor
     * 
     * @since 1.0.0
     */
    public function __construct() {
      $this->hooks();
    }

    /**
     * Main Instance.
     *
     * Ensures only one instance is loaded or can be loaded.
     *
     * @since 1.0.0
     */
    public static function instance() {
      if ( is_null( self::$_instance ) ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    /**
     * Hooks
     * 
     * @since 1.0.0
     */
    public function hooks() {
      // hook reading time
      add_filter('the_content', array($this, 'insert_to_the_content'));
      // create reading time shortcode
      add_shortcode('time_to_read', array($this, 'shortcode'));
    }

    /**
     * Hook reading time into the_content
     * 
     * @since 1.0.0
     */
    public function insert_to_the_content($content) {
      $post_id = get_the_ID();
      $settings = \lc\timetoread\includes\data\TimeToReadDataMeta::instance($post_id);

      // check for disabled automatic output
      if( isset($settings['disable_automatic_output']) && $settings['disable_automatic_output'] === 1 ) {
        return;
      }

      $reading_time_output = \lc\timetoread\includes\TimeToReadRender::instance($post_id)->render_template(true);

      return $reading_time_output . $content;
    }

    /**
     * Create shortcode
     * 
     * @since 1.0.0
     */
    public function shortcode($atts = [] ) {
      $post_id = get_the_ID();

      if( !$post_id ) {
        return;
      }

      $defaults = [];
      $atts = shortcode_atts( $defaults, $atts, 'time_to_read' );

      return \lc\timetoread\includes\TimeToReadRender::instance($post_id)->render_template(true); 
    }

    /**
     * Reading time block
     * 
     * @since 1.0.0
     */
    public function reading_time_block($post_id = 0, $attributes = []) {
      if( $post_id === 0 ) {
        $post_id = get_the_ID();
      }

      if(!$post_id) { 
        return;
      }

      $styles = [];

      if ( isset( $attributes['fontSize'] ) ) {
        $styles[] = 'font-size: ' . esc_attr( $attributes['fontSize'] );
      }

      if ( isset( $attributes['style']['color']['background'] ) ) {
        $styles[] = 'background-color: ' . esc_attr( $attributes['style']['color']['background'] );
      }

      if ( isset( $attributes['textColor'] ) ) {
        $styles[] = 'color: ' . esc_attr( $attributes['textColor'] );
      } elseif (isset($attributes['style']['color']['text'])) {
        $styles[] = 'color: ' . esc_attr( $attributes['style']['color']['text'] );
      }

      $style_attr = implode( '; ', array_filter( $styles ) );

      $args = [
        'style' => $style_attr
      ];

      return \lc\timetoread\includes\TimeToReadRender::instance($post_id)->render_template(true, $args);
    }
    
  }

}