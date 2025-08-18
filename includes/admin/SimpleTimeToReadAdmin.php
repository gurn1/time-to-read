<?php
/**
 * Installation related hooks and actions
 * 
 * @version 1.0.0
 */

namespace lc\sttrlsc\includes\admin;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadAdmin') ) {
  class SimpleTimeToReadAdmin {
    
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
    }

    /**
     * Hooks
     * 
     * @since 1.0.0
     */
    public function hooks() {

      /**
       * Register options pages
       * 
       * @since 1.0.0
       */
      new \lc\sttrlsc\includes\admin\options\SimpleTimeToReadOptionMain();

      /**
       * Register metaboxes
       * 
       * @since 1.0.0
       */
      new \lc\sttrlsc\includes\admin\metaboxes\SimpleTimeToReadMetaBoxSidebar();
      
      /**
       * Register admin scripts
       * 
       * @since 1.0.0
       */
      add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

      /**
       * Register admin styles
       * 
       * @since 1.0.0
       */
      add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));

    }

    /**
     * Enqueue primary scripts
     * 
     * @since 1.0.0
     */
    public function enqueue_scripts() {
      // Main admin js script
      // if( ! wp_script_is('timetoread-admin', 'enqueued') ) {
      //   wp_enqueue_script('timetoread-admin', STTRLSC_ASSETS_ADMIN_URL . 'js/timetoread-admin.js', array(), '1.0.0', true);
      // }

      // wp_localize_script(
      //   'timetoread-admin',
      //   'timetoreadObject',
      //   array(
      //     'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
      //   )
      // );

    }

    /**
     * Enqueue primary stylesheets
     * 
     * @since 1.0.0
     */
    public function enqueue_styles() {
      // locations frontend css stylesheet
      if( ! wp_style_is('timetoread-admin-css', 'registered') ) {
        wp_register_style('timetoread-admin-css', STTRLSC_ASSETS_ADMIN_URL . 'css/timetoread-admin.css', array(), '1.0.0');
      }

    }

  }
}