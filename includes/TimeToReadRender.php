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
        self::$post_id = $post_id;
      }
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

      // Merge with post meta overriding defaults
      $merged = array_merge($options, $meta);

      return $merged;
    }

    /**
     * Render template
     * 
     * @since 1.0.0
     * @return string
     */
    public function render_template($return = false) {
      $settings = $this->get_settings();
      $calculation = $this->get_calculation();

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