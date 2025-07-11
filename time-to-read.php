<?php
/**
 * Plugin Name: Time to Read
 * Description: Time To Read is a lightweight plugin that adds an estimated reading time to your posts, pages, and custom post types. Improve user experience by giving your readers a quick idea of how long your content will take to read. The reading time is calculated based on the word count of your content and can be easily customized to suit your site’s tone and layout.
 * Version: 1.0.0
 * Author: Luke Clifton
 * Author URI: https://www.lscwebdesign.co.uk
 * Text Domain: time-to-read
 * Domain Path: /i18n/
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.6
 * Requires PHP: 8.0
 *
 * @package time-to-read
 */

defined( 'ABSPATH' ) || exit;

if( ! defined( 'TIMETOREAD_FILE' ) ) {
  define( 'TIMETOREAD_FILE', __FILE__ );
}

/**
 * Autoloader
 * 
 * @since 1.0.0
 */
require __DIR__ . '/vendor/autoload.php';

if( ! function_exists('time_to_read')) {
  function time_to_read() {
    return \lc\timetoread\includes\TimeToReadClass::instance();
  }

  time_to_read();
}