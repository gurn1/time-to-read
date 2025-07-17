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
    
  }

}