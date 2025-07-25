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

      // register blocks
      add_action('init', array($this, 'register_blocks'));
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
     * Register blocks
     * 
     * @since 1.0.0
     */
    public function register_blocks() {

      wp_register_script(
        'lc-time-to-read-editor-script',
        TIMETOREAD_URL . 'blocks/reading-time/index.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-data', 'wp-core-data', 'wp-api-fetch'),
        filemtime(TIMETOREAD_ABSPATH . 'blocks/reading-time/index.js')
      );

      // Read blocks
      register_block_type( 
        TIMETOREAD_ABSPATH . 'blocks/reading-time/block.json', 
        array( 'render_callback' => array($this, 'reading_time_block'))
      );
    }

    /**
     * Reading time block
     * 
     * @since 1.0.0
     */
    public function reading_time_block($attributes, $content) {
      $post_id = get_the_ID();

      if(!$post_id) { 
        return;
      }

      return \lc\timetoread\includes\TimeToReadRender::instance($post_id)->render_template(true);
    }

    /**
     * Rest api callback
     * 
     * @since 1.0.0
     */
    public function rest_api_callback_ouput($request) {
      $post_id = isset($request['id']) ? absint($request['id']) : 0;

      if(!$post_id) {
        return new \WP_Error('invalid_id', 'Invalid post ID', array('status' => 400));
      }

      $html = \lc\timetoread\includes\TimeToReadRender::instance($post_id)->render_template(true);

      return new \WP_REST_Response( $html, 200, [ 'Content-Type' => 'text/html' ] );
    }
    
  }

}