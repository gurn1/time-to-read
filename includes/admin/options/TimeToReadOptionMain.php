<?php
/**
 * Installation related hooks and actions
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\admin\options;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadOptionMain') ) {
  class TimeToReadOptionMain extends TimeToReadAbstractOption {

    /**
     * Menu slug
     * 
     * @since 1.0.0
     * @return string
     */
    public static $menu_slug = 'time-to-read-main';

    /**
     * Menu title
     * 
     * @since 1.0.0
     * @return string
     */
    public static $menu_title = 'Time to Read';

    /**
     * Setting name
     * 
     * @since 1.0.0
     * @return array
     */
    public static $settings_name = ['time_to_read_settings_main'];

    /**
     * Register fields
     * 
     * @since 1.0.0
     * @return string
     */
    protected function register_fields($settings_name) {

    }

  }
}