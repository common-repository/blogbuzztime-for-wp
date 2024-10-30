<?php
/*
  Plugin Name: BlogBuzzTime
  Plugin URI: http://blogbuzztime.com
  Description: Show current readers on your blog
  Version: 1.1
  Author: BlogBuzzTime
  Author URI: http://blogbuzztime.com
  License: GPL2
  Text Domain: blogbuzztime
 */

include  dirname(__FILE__) . '/functions.php';
include  dirname(__FILE__) . '/class/BlogBuzzTime.php';
include  dirname(__FILE__) . '/class/BlogBuzzTime_widget.php';
include  dirname(__FILE__) . '/class/BlogBuzzTime_widgetCounter.php';
include  dirname(__FILE__) . '/class/BlogBuzzTime_widgetPicture.php';
include  dirname(__FILE__) . '/class/CreditRoom_widget.php';
$blogbuzztime = Blogbuzztime::getInstance(plugins_url('/', __FILE__), dirname(__FILE__));

register_activation_hook( __FILE__, 'blogBuzzTime_Install' );
register_deactivation_hook ( __FILE__, 'blogBuzzTime_Uninstall' );

// register widgets
add_action('widgets_init', 'blogbuzztime_widget');
add_action('widgets_init', 'blogbuzztime_widgetCounter');
add_action('widgets_init', 'blogbuzztime_widgetPicture');

// load language
load_plugin_textdomain( 'blogbuzztime', false,  dirname( plugin_basename( __FILE__ ) ) . '/languages/' ) ;

