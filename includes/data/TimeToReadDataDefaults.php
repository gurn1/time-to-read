<?php
/**
 * Defaults data controller
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\data;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadDataDefaults') ) {
  class TimeToReadDataDefaults {

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
    public static function instance($type = 'option'): array {
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
        'settings_general_text' => 'Testing default',
        'settings_general_posttype' => [
          'post' => 1,
          'page' => 1,
          'product' => 1
        ]
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