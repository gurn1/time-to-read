<?php
/**
 * Defaults data controller
 * 
 * @version 1.0.0
 */

namespace lc\stimetoreadlsc\includes\data;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadDataDefaults') ) {
  class SimpleTimeToReadDataDefaults {

    /**
     * Type
     * 
     * @since 1.0.0
     * @return string
     */
    protected static $type = '';

    /**
     * Construct
     * 
     * @since 1.0.0
     */
    public function __construct($type = 'option') {
      self::$type = $type;
    }

    /**
     * Instance
     * 
     * @since 1.0.0
     * @return array
     */
    public static function instance($type = 'option') {
      return (new self($type))->get_defaults();
    }

    /**
     * Options defaults
     * 
     * @since 1.0.0
     * @return array
     */
    protected function options() {
      return [
        'posttype_selector' => [
          'post' => '1',
          'page' => '0'
        ],
        'reading_time_text' => 'Estimated reading time: '
      ];
    }

    /**
     * Meta defaults
     * 
     * @since 1.0.0
     * @return array
     */
    protected function meta() {
      return [];
    }

    /**
     * Return all defaults based on type.
     * 
     * @return array
     */
    public function get_defaults() {
      return self::$type === 'post' ? $this->meta() : $this->options();
    }

  }
}