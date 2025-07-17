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
    public static $title = 'Time to Read';

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
        'disable_automatic_output' => array(
          'type' => 'checkbox',
          'label' => esc_html__('Disbale automatic output', 'time-to-read')
        ),
        'reading_time_text' => array(
          'type' => 'text',
          'label' => esc_html__('Text', 'time-to-read'),
          'description' => esc_html__('Leave this field empty to use default text, set in the settings section.', 'time-to-read')
        )
      );
    }

  }
}