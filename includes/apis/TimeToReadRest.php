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
        'callback' => array( $this, 'render_callback' ),
        'permission_callback' => '__return_true'
      ));
    }

    /**
     * Render callback
     * 
     * @since 1.0.0
     */
    public function render_callback($request) {
      $post_id = isset($request['id']) ? absint($request['id']) : 0;

      if(!$post_id) {
        return new \WP_Error('invalid_id', 'Invalid post ID', array('status' => 400));
      }


      $html = \lc\timetoread\includes\TimeToReadIntegrate::instance()->reading_time_block($post_id);

      if( !$html ) {
        return new \WP_Error('no_template', 'Template not found', array('status' => 404)); 
      }

      $allowed_html = wp_kses_allowed_html( 'post' );
      $safe_html = wp_kses($html, $allowed_html);

      return new \WP_REST_Response( $safe_html, 200, [ 'Content-Type' => 'text/html' ] );
    }
    
  }

}