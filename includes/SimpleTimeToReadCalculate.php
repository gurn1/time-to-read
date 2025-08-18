<?php
/**
 * Make calculations for reading count
 * 
 * @version 1.0.0
 */

namespace lc\sttrlsc\includes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadCalculate') ) {
  class SimpleTimeToReadCalculate {

    /**
     * The single instance of the class.
     * 
     * @since 1.0.0
     */
	  protected static $_instance = null;

    /**
     * Post id
     * 
     * @since 1.0.0
     * @return int
     */
    protected static $post_id = 0;

    /**
     * Words per minute
     * 
     * @since 1.0.0
     * @return int
     */
    protected static $words_per_minute = 0;

    /**
     * Constructor
     * 
     * @since 1.0.0
     */
    public function __construct($post_id,  $wpm = 200 ) {
      self::$post_id = $post_id;
      self::$words_per_minute = $wpm;
    }

    /**
     * Main Instance.
     *
     * Ensures only one instance is loaded or can be loaded.
     *
     * @since 1.0.0
     */
    public static function instance($post_id, $wpm = 200) {
      if ( is_null( self::$_instance ) ) {
        self::$_instance = new self($post_id, $wpm);
      }
      return self::$_instance;
    }

    /**
     * Get the content
     * 
     * @since 1.0.0
     * @return string
     */
    public function get_the_content() {
      $post = get_post(self::$post_id);

      if( !$post ) {
        return;
      }

      return property_exists($post, 'post_content') ? $post->post_content : '';
    }

    /**
     *  Calculate the reading time
     * 
     * @since 1.0.0
     * @return int|WP_Error
     */
    public function get_reading_time() {
      $content = $this->get_the_content();

      if( !$content ) {
        return 0;
      }

      $trimmed_text = trim( wp_strip_all_tags( strip_shortcodes($content) ) );
      
      if( empty($trimmed_text) ) {
        return 0;
      }

      $word_count = str_word_count( $trimmed_text );
      $minutes = round( $word_count / self::$words_per_minute, 1 );

      return $minutes;
    }
    
  }

}