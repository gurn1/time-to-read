<?php
/**
 * Primary sidebar metabox
 * 
 * @version 1.0.0
 */

namespace lc\sttrlsc\includes\admin\metaboxes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('SimpleTimeToReadMetaBoxSidebar') ) {
  class SimpleTimeToReadMetaBoxSidebar extends SimpleTimeToReadAbstractMetabox {

    /**
     * ID
     * 
     * @since 1.0.0
     * @return string
     */
    public static $ID = 'simple-time-to-read-lsc-sidebar';

    /**
     * Title
     * 
     * @since 1.0.0
     * @return string
     */
    public static $title = 'Simple Time to Read';

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
          'label' => esc_html__('Disbale automatic output', 'simple-time-to-read-lsc')
        ),
        'reading_time_text' => array(
          'type' => 'text',
          'label' => esc_html__('Text', 'simple-time-to-read-lsc'),
          'description' => esc_html__('Leave this field empty to use default text, set in the settings section.', 'simple-time-to-read-lsc')
        )
      );
    }

    /**
     * check if metabox has been disabled.
     * 
     * @since 1.0.0
     * @return bool
     */
    public static function metabox_disabled_check( ) {
      global $post;

      if( !is_object($post) ) {
        return;
      }

      $post_type = property_exists($post, 'post_type') ? $post->post_type : '';
      $options = \lc\sttrlsc\includes\data\SimpleTimeToReadDataOptions::instance();
      $post_type_checker = isset($options['posttype_selector']) ? $options['posttype_selector'] : [];

      if( is_array($post_type_checker) && array_key_exists($post_type, $post_type_checker) && $post_type_checker[$post_type] === '1' ) {
        return true;
      }

      return false;
    }

  }
}