<?php
/**
 * Primary sidebar metabox
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\admin\metaboxes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadMetaBoxSidebar') ) {
  class TimeToReadMetaBoxSidebar extends TimeToReadAbstractMetabox {

    /**
     * ID
     * 
     * @since 1.0.0
     * @return string
     */
    public static $ID = 'time-to-read-sidebar';

    /**
     * Title
     * 
     * @since 1.0.0
     * @return string
     */
    public static $title = 'Time To Read options';

    /**
     * Where to show metabox
     * 
     * @since 1.0.0
     * @return array
     */
    public static $screen = [];

    /**
     * Context
     * 
     * @since 1.0.0
     * @return string
     */
    public static $context = 'side';

    /**
     * Priority
     * 'high', 'core', 'default', 'low'
     * 
     * @since 1.0.0
     * @return string
     */
    public static $priority = 'default';

    /**
     * Register fields
     * 
     * @since 1.0.0
     * @return string
     */
    protected static function register_fields() {
      return array(
        'disable_post' => array(
          'type' => 'checkbox',
          'label' => esc_html__('Turn off', 'time-to-read')
        ),
        'test' => array(
          'type' => 'text',
          'label' => esc_html__('Field', 'time-to-read')
        ),
        'select_field' => array(
          'type' => 'select',
          'label' => esc_html__('Select', 'time-to-read'),
          'choices' => array(
            'one' => 'One',
            'two' => 'Two',
            'three' => 'Three',
            'four' => 'Four'
          )
        ),
        'date' => array(
          'type' => 'datepicker',
          'label' => esc_html__('Date', 'time-to-read')
        ),
        'color' => array(
          'type' => 'colorpicker',
          'label' => esc_html__('Colour Picker', 'time-to-read')
        ),
        'radio' => array(
          'type' => 'radio',
          'label' => esc_html__('Radio', 'time-to-read'),
          'choices' => array(
            'one' => 'One',
            'two' => 'Two',
            'three' => 'Three',
            'four' => 'Four'
          )
        )
      );
    }

  }
}