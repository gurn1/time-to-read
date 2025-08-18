<?php
/**
 * Main plugin class
 * 
 * @version 1.0.0
 */

namespace lc\sttrlsc\includes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadClass') ) {
  class SimpleTimeToReadClass {
    
    /**
     * Version
     * 
     * @since 1.0.0
     */
    public $version = '1.0.0';

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
      $this->define_constants();
      $this->includes();
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
     * Includes
     * 
     * @since 1.0.0
     */
    public function includes() {
      // Instantiate admin class
      \lc\sttrlsc\includes\admin\SimpleTimeToReadAdmin::instance();

      // Integrate into wp
      \lc\sttrlsc\includes\SimpleTimeToReadIntegrate::instance();

      // Create rest endpoints
      \lc\sttrlsc\includes\apis\SimpleTimeToReadRest::instance();

      include_once STTRLSC_ABSPATH . 'helpers/general-helpers.php';
    }

    /**
     * Constants
     * 
     * @since 1.0.0
     */
    public function define_constants() {
      // absolute path
      $this->define( 'STTRLSC_ABSPATH', dirname(STTRLSC_FILE) . '/' );
      // version
      $this->define( 'STTRLSC_VERSION', $this->version );
      // text domain
      $this->define( 'STTRLSC_TEXT_DOMAIN', 'simple-time-to-read-lsc' );
      // admin url
      $this->define( 'STTRLSC_URL', $this->plugin_url() );
      // Assets folder public
      $this->define( 'STTRLSC_ASSETS_PUBLIC_URL', STTRLSC_URL . 'assets/public/' );
      // assets folder admin
      $this->define( 'STTRLSC_ASSETS_ADMIN_URL', STTRLSC_URL . 'assets/admin/' );
      // images folder
      $this->define( 'STTRLSC_IMAGES', STTRLSC_URL . 'images/');
      // templates folder
      $this->define( 'STTRLSC_TEMPLATES',  STTRLSC_ABSPATH . 'templates/');
      // Options data name
      $this->define( 'STTRLSC_OPTION_NAME', 'simple_time_to_read_lsc_options');
      // Meta data name
      $this->define( 'STTRLSC_META_NAME', 'simple_time_to_read_lsc_postmeta');
      // Define block folder path
      $this->define( 'STTRLSC_BLOCK_PATH', STTRLSC_ABSPATH . 'blocks/');
    }

    /**
     * Hooks
     * 
     * @since 1.0.0
     */
    public function hooks() {

      /**
       * Register blocks
       * 
       * @since 1.0.0
       */
      new \lc\sttrlsc\includes\blocks\SimpleTimeToReadBlockMain();

      /**
       * Register frontend scripts
       * 
       * @since 1.0.0
       */
      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

      /**
       * Register frontend styles
       * 
       * @since 1.0.0
       */
      add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    /**
     * Enqueue primary scripts
     * 
     * @since 1.0.0
     */
    public function enqueue_scripts() {

      // Main frontend js script

    }

    /**
     * Enqueue primary stylesheets
     * 
     * @since 1.0.0
     */
    public function enqueue_styles() {
      $options = \lc\sttrlsc\includes\data\SimpleTimeToReadDataOptions::instance();
      $disable_stylesheet = isset($options['disable_stylesheet']) && sanitize_text_field($options['disable_stylesheet']) === '1' ? true : false; 

      // locations frontend css stylesheet
      if( ! wp_style_is('sttrlsc-css', 'enqueued') && $disable_stylesheet !== true ) {
        wp_enqueue_style('sttrlsc-css', STTRLSC_ASSETS_PUBLIC_URL . 'css/style.css', array(), '1.0.0');
      }

    }

    /**
     * Define constants is not set
     * 
     * @since 1.0.0
     */
    public function define($name, $value) {
      if( ! defined($name) ) {
        define($name, $value);
      }
    }

    /**
     * Get the plugin url.
     *
     * @since 1.0.0
     */
    public function plugin_url() {
      return trailingslashit( plugins_url( '/', STTRLSC_FILE ) );
    }

    /**
     * Get the plugin path.
     *
     * @since 1.0.0
     */
    public function plugin_path() {
      return trailingslashit( plugin_dir_path( STTRLSC_FILE ) );
    }
    
  }

}