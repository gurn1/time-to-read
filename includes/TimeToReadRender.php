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

if( ! class_exists('TimeToReadReder') ) {
  class TimeToReadRender {

    /**
     * Post id
     * 
     * @since 1.0.0
     * @return int
     */
    protected static $post_id = 0;

    /**
     * Post type
     * 
     * @since 1.0.0
     * @return string
     */
    protected static $post_type = '';

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
    public function __construct($post_id = 0) {
      if( $post_id === 0 ) {
        global $post;

        self::$post_id = property_exists($post, 'ID') ? $post->ID : 0;
      } else {
        $post = get_post($post_id);
        self::$post_id = $post_id;
      }

      self::$post_type = property_exists($post, 'post_type') ? $post->post_type : '';
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
     * Get the options
     * 
     * @since 1.0.0
     * @return array
     */
    public function get_options() {
      return \lc\timetoread\includes\data\TimeToReadDataOptions::instance();
    }

    /**
     * Get post meta
     * 
     * @since 1.0.0
     * @return array
     */
    public function get_meta() {
      return \lc\timetoread\includes\data\TimeToReadDataMeta::instance(self::$post_id);
    }

    /**
     * Get calculation
     * 
     * @since 1.0.0
     * @return float|int
     */
    public function get_calculation() {
      return \lc\timetoread\includes\TimeToReadCalculate::instance(self::$post_id)->get_reading_time();
    }

    /**
     * Get settings
     * 
     * @since 1.0.0
     * @return array
     */
    public function get_settings() {
      $options = $this->get_options();
      $meta = $this->get_meta();

      // Ensure both are arrays to avoid issues
      $options = is_array($options) ? $options : [];
      $meta    = is_array($meta) ? $meta : [];

      // reset option defaults if meta empty 
      if( isset($meta['reading_time_text']) && empty($meta['reading_time_text'] ) ) {
        $meta['reading_time_text'] = isset($options['reading_time_text']) ? $options['reading_time_text'] : '';
      }

      // Merge with post meta overriding defaults
      $merged = array_merge($options, $meta);

      return $merged;
    }

    /**
     * Calculation output
     * 
     * @since 1.0.0
     */
    public function calculation_output() {
      $raw_calculation = $this->get_calculation();
      $settings = $this->get_settings();
      $output = '';

      if($raw_calculation < 1) {
        $output = __('Less than 1 minute', 'time-to-read');
      } elseif($raw_calculation === 1) {
        $output = sprintf( __( '%s Minute', 'time-to-read' ), number_format_i18n( $raw_calculation, 1 ) );
      } else {
        $output = sprintf( __( '%s Minutes', 'time-to-read' ), number_format_i18n( $raw_calculation, 1 ) );
      }

      return $output;
    }

    /**
     * Render template
     * 
     * @since 1.0.0
     * @return string
     */
    public function render_template($return = false) {
      $settings = $this->get_settings();
      $calculation = $this->calculation_output();
      $text = isset($settings['reading_time_text']) ? $settings['reading_time_text'] : '';

      // check current post type is applicable
      if( !isset($settings['posttype_selector']) || !is_array($settings['posttype_selector']) || !array_key_exists(self::$post_type, $settings['posttype_selector']) || $settings['posttype_selector'][self::$post_type] === 0  ) {
        return;
      }
      
      $template = TIMETOREAD_TEMPLATES . 'front.php';

      if( !file_exists($template) ) {
        return;
      }

      if( $return === true ) {
        ob_start();
        include $template;
        $content = ob_get_clean();
        return $content;
      } else {
        include $template;
      }
    }
    
  }

}