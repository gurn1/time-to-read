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
    }

    /**
     * Hook reading time into the_content
     * 
     * @since 1.0.0
     */
    public function insert_to_the_content() {
      $post_id = get_the_ID();
      return \lc\timetoread\includes\TimeToReadRender::instance($post_id)->render_template(true);
    }
    
  }

}