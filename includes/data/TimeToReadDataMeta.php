<?php
/**
 * Meta data controller
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\data;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadDataMeta') ) {
  class TimeToReadDataMeta {

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
    protected static $meta_values = [];

    /**
     * Post id
     * 
     * @since 1.0.0
     * @return int
     */
    public static $post_id = 0;

    /**
     * Construct
     * 
     * @since 1.0.0
     */
    public function __construct($post_id) {
      self::$post_id = $post_id;
      $this->set_defaults();
      $this->set_meta();
    }

    /**
     * instance
     * 
     * @since 1.0.0
     * @return mixed
     */
    public static function instance($post_id) {
      return (new self($post_id))->get_meta_data();
    }

    /**
     * Get option field
     * 
     * @since 1.0.0
     * @return array|false
     */
    protected function set_meta() {
      $meta = get_post_meta(self::$post_id, TIMETOREAD_META_NAME, true);
      self::$meta_values = is_array($meta) && !empty($meta) ? $meta : [];
    }

    /**
     * Get defaults
     * 
     * @since 1.0.0
     * @return array
     */
    protected function set_defaults() {
      self::$defaults = \lc\timetoread\includes\data\TimeToReadDataDefaults::instance('post');
    }

    /**
     * Merge keys
     * 
     * @sicne 1.0.0
     * @return array|false
     */
    protected function merge_keys() {
      $defaults = is_array(self::$defaults) ? self::$defaults : [];
      $meta  = is_array(self::$meta_values) ? self::$meta_values : [];
      
      return array_keys(array_merge($defaults, $meta));
    }

    /**
     * Get all meta with defaults applied
     * 
     * @since 1.0.0
     * @return array|false
     */
    public function get_meta_data() {
      $meta_keys = $this->merge_keys();
      $output = [];

      foreach ($meta_keys as $key) {
        if (array_key_exists($key, self::$meta_values)) {
          $output[$key] = self::$meta_values[$key];
        } elseif (array_key_exists($key, self::$defaults)) {
          $output[$key] = self::$defaults[$key];
        }
      }
      var_dump($output);
      return $output;
    }

    /**
     * Get single option
     * 
     * @since 1.0.0
     * @param string $key
     * @return mixed
     */
    public function get_meta($key) {
      $meta = $this->get_meta_data();

      return array_key_exists($key, $meta) ? $meta[$key] : '';
    }

  }
}