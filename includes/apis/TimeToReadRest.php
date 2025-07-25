<?php
/**
 * Add restful endpoints
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\apis;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadRest') ) {
  class TimeToReadRest {

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
      add_action('rest_api_init', array($this, 'render_endpoint'));
    }

    /**
     * Add endpoint for base output
     * 
     * @since 1.0.0
     */
    public function render_endpoint() {
      register_rest_route('time-to-read/v1', '(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => array( \lc\timetoread\includes\TimeToReadIntegrate::instance(), 'rest_api_callback_ouput' ),
        'permission_callback' => '__return_true'
      ));
    }
    
  }

}