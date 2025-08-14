<?php
/**
 * Options data controller
 * 
 * @version 1.0.0
 */

namespace lc\stimetoreadlsc\includes\data;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadDataOptions') ) {
  class SimpleTimeToReadDataOptions {

    /**
     * Defaults
     * 
     * @since 1.0.0
     */
    protected static $defaults = [];

    /**
     * Option values
     * 
     * @since 1.0.0
     */
    protected static $option_values = [];

    /**
     * Construct
     * 
     * @since 1.0.0
     */
    public function __construct() {
      $this->set_defaults();
      $this->set_options();
    }

    /**
     * instance
     * 
     * @since 1.0.0
     * @return mixed
     */
    public static function instance() {
      return (new self())->get_options();
    }

    /**
     * Get option field
     * 
     * @since 1.0.0
     * @return array|false
     */
    protected function set_options() {
      $option = get_option(STIMETOREADLSC_OPTION_NAME);
      self::$option_values = is_array($option) && !empty($option) ? $option : [];
    }

    /**
     * Get defaults
     * 
     * @since 1.0.0
     * @return array
     */
    protected function set_defaults() {
      self::$defaults = \lc\stimetoreadlsc\includes\data\SimpleTimeToReadDataDefaults::instance('option');
    }

    /**
     * Merge keys
     * 
     * @sicne 1.0.0
     * @return array|false
     */
    protected function merge_keys() {
      $defaults = is_array(self::$defaults) ? self::$defaults : [];
      $options  = is_array(self::$option_values) ? self::$option_values : [];
      
      return array_keys(array_merge($defaults, $options));
    }

    /**
     * Get all options with defaults applied
     * 
     * @since 1.0.0
     * @return array|false
     */
    public function get_options() {
      $option_keys = $this->merge_keys();
      $output = [];

      foreach ($option_keys as $key) {
        if (array_key_exists($key, self::$option_values)) {
          $output[$key] = self::$option_values[$key];
        } elseif (array_key_exists($key, self::$defaults)) {
          $output[$key] = self::$defaults[$key];
        }
      }
      
      return $output;
    }

    /**
     * Get single option
     * 
     * @since 1.0.0
     * @param string $key
     * @return mixed
     */
    public function get_option($key) {
      $options = $this->get_options();

      return array_key_exists($key, $options) ? $options[$key] : '';
    }

  }
}