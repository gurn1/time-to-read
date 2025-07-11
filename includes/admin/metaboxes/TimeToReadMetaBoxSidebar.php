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
          'label' => esc_html__('Turn off', TIMETOREAD_TEXT_DOMAIN)
        ),
        'test' => array(
          'type' => 'text',
          'label' => esc_html__('Field', TIMETOREAD_TEXT_DOMAIN)
        ),
        'select_field' => array(
          'type' => 'select',
          'label' => esc_html__('Select', TIMETOREAD_TEXT_DOMAIN),
          'choices' => array(
            'one' => 'One',
            'two' => 'Two',
            'three' => 'Three',
            'four' => 'Four'
          )
        ),
        'date' => array(
          'type' => 'datepicker',
          'label' => esc_html__('Date', TIMETOREAD_TEXT_DOMAIN)
        ),
        'color' => array(
          'type' => 'colorpicker',
          'label' => esc_html__('Colour Picker', TIMETOREAD_TEXT_DOMAIN)
        ),
        'radio' => array(
          'type' => 'radio',
          'label' => esc_html__('Radio', TIMETOREAD_TEXT_DOMAIN),
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