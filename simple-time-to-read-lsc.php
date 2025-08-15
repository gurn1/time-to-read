<?php
/**
 * Plugin Name: Simple Time to Read LSC
 * Description: Simple Time To Read LSC is a lightweight plugin that adds an estimated reading time to your posts, pages, and custom post types. Improve user experience by giving your readers a quick idea of how long your content will take to read. The reading time is calculated based on the word count of your content and can be easily customized to suit your site’s tone and layout.
 * Version: 1.0.0
 * Author: Luke Clifton
 * Author URI: https://www.lscwebdesign.co.uk
 * Text Domain: simple-time-to-read-lsc
 * Domain Path: /i18n
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.6
 * Requires PHP: 8.0
 *
 * @package simple-time-to-read-lsc
 */

defined( 'ABSPATH' ) || exit;

if( ! defined( 'STIMETOREADLSC_FILE' ) ) {
  define( 'STIMETOREADLSC_FILE', __FILE__ );
}

/**
 * Autoloader
 * 
 * @since 1.0.0
 */
require __DIR__ . '/vendor/autoload.php';

if( ! function_exists('simple_time_to_read_lsc')) {
  function simple_time_to_read_lsc() {
    return \lc\stimetoreadlsc\includes\SimpleTimeToReadClass::instance();
  }

  simple_time_to_read_lsc();
}
