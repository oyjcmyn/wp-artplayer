<?php
/**
 * Plugin Name:  Artplayer for wordpress
 * Plugin URI:   https://discuss.miued.com/
 * Description:  Artplayer是一款易于使用且功能丰富的 HTML5 视频播放器，支持弹幕，播放器的大部分功能控件都支持自定义。
 * Author:       蓝色早晨
 * Author URI:   https://discuss.miued.com/
 * Version:      1.1
 * Text Domain:  wp-crontrol
 * Domain Path:  /languages/
 * Requires PHP: 7.0
 * License:      GPL v2 or later
 *
 * LICENSE
 * This file is part of WP Crontrol.
 *
 * WP Crontrol is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined( 'ABSPATH' ) || exit;

define( 'WP_ARTDPLAYER_VERSION', '1.1' );
define( 'WP_ARTDPLAYER_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
define( 'WP_ARTDPLAYER_INC_PATH', realpath( WP_ARTDPLAYER_PATH . 'inc/' ) . '/' );
define( 'WP_ARTDPLAYER_URL', plugin_dir_url( __FILE__ ) );

$file=WP_ARTDPLAYER_PATH .'config.php';
if(file_exists($file)){
  include $file;//载入配置
}
require_once WP_ARTDPLAYER_INC_PATH . 'main.php';
require_once WP_ARTDPLAYER_INC_PATH . 'admin_menus.php';

register_activation_hook( __FILE__, array( 'ART_MAIN_MI', 'install' ) );

add_action( 'plugins_loaded', 'artplayer_load_textdomain');
function artplayer_load_textdomain() {
        // prefix
        $prefix = basename( dirname( plugin_basename( __FILE__ ) ) );
        $locale = get_locale();
        $dir    = WP_ARTDPLAYER_PATH.'/languages';
        $mofile = false;

        $globalFile = WP_LANG_DIR . '/plugins/' . $prefix . '-' . $locale . '.mo';
        $pluginFile = $dir . '/artplayer-'. $locale . '.mo';

        if ( file_exists( $globalFile ) ) {
            $mofile = $globalFile;
        } else if ( file_exists( $pluginFile ) ) {
            $mofile = $pluginFile;
        }

        if ( $mofile ) {
            load_textdomain( 'wpartplayer', $mofile );
        }
}

add_filter( 'plugin_action_links', 'add_settings_link', 10, 2 );
function add_settings_link( $links, $file ) {
    if ( $file === 'wp-artplayer/wp-artplayer.php' && current_user_can( 'manage_options' ) ) {
      $url = admin_url( 'admin.php?page=artplayerset' );
      $links = (array) $links;
      $links[] = sprintf( '<a href="%s">%s</a>', $url, __( 'Settings', 'classic-editor' ) );
    }

    return $links;
  }








