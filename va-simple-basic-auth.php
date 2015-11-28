<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: VA Simple Basic Auth
Plugin URI: https://github.com/VisuAlive/va-simple-basic-auth
Description: Simply activate the plugin, can set up a basic auth to management screen.
Author: KUCKLU
Version: 1.0.0
Author URI: http://visualive.jp/
Text Domain: va-simple-basic-auth
Domain Path: /languages
GitHub Plugin URI: https://github.com/VisuAlive/va-simple-basic-auth
GitHub Branch: master
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

VisuAlive WordPress Plugin, Copyright (C) 2014 VisuAlive and KUCKLU.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
/**
 * VA SIMPLE BASIC AUTH
 *
 * @package WordPress
 * @subpackage VA Simple Basic Auth
 * @since VA Shared Count 1.0.0
 * @author KUCKLU <kuck1u@visualive.jp>
 * @copyright Copyright (c) 2014 KUCKLU, VisuAlive.
 * @license http://opensource.org/licenses/gpl-2.0.php GPLv2
 * @link http://visualive.jp/
 */
$vasba_plugin_data = get_file_data( __FILE__, array('ver' => 'Version', 'langs' => 'Domain Path', 'mo' => 'Text Domain' ) );
define( 'VA_SIMPLE_BASIC_AUTH_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'VA_SIMPLE_BASIC_AUTH_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'VA_SIMPLE_BASIC_AUTH_DOMAIN', dirname( plugin_basename(__FILE__) ) );
define( 'VA_SIMPLE_BASIC_AUTH_VERSION', $vasba_plugin_data['ver'] );
define( 'VA_SIMPLE_BASIC_AUTH_TEXTDOMAIN', $vasba_plugin_data['mo'] );

if ( ! class_exists( 'VA_SIMPLE_BASIC_AUTH' ) ) :

	/**
	 * VA_SIMPLE_BASIC_AUTH
	 */
	class VA_SIMPLE_BASIC_AUTH {
		/**
		 * [__construct description]
		 */
		function __construct() {
			register_activation_hook( __FILE__, array( $this, '_vasba_activation' ) );
			register_uninstall_hook( __FILE__, array( $this, '_vasba_uninstall' ) );
			add_action( 'login_init', array( $this, '_vasba_basic_auth' ), 0 );
		}

		/**
		 * [_vasba_edit_htaccess description]
		 * @link https://github.com/wokamoto/wp-basic-auth
		 * @param  boolean $action .
		 * @return null
		 */
		public function _vasba_edit_htaccess( $action = false ) {
			$htaccess_rewrite_rule = <<< EOM
# BEGIN VA SIMPLE BASIC AUTH
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule ^(.*) - [E=HTTP_AUTHORIZATION:%1]
</IfModule>
# END VA SIMPLE BASIC AUTH


EOM;

			if ( ! file_exists( ABSPATH.'.htaccess' ) || ! $action ) {
				return;
			}
			$htaccess = file_get_contents( ABSPATH.'.htaccess' );

			switch ( $action ) {
				case 'activation':
					if ( strpos( $htaccess, $htaccess_rewrite_rule ) !== false ) {
						return;
					}
					file_put_contents( ABSPATH.'.htaccess', $htaccess_rewrite_rule . $htaccess );
					break;
				case 'uninstall':
					if ( strpos( $htaccess, $htaccess_rewrite_rule ) === false ) {
						return;
					}
					file_put_contents( ABSPATH.'.htaccess', str_replace( $htaccess_rewrite_rule, '', $htaccess ) );
					break;
			}
		}

		/**
		 * [_vasba_activation description]
		 * @return [type] [description]
		 */
		function _vasba_activation() {
			self::_vasba_edit_htaccess( 'activation' );
			return;
		}

		/**
		 * [_vasba_uninstall description]
		 * @return [type] [description]
		 */
		function _vasba_uninstall() {
			self::_vasba_edit_htaccess( 'uninstall' );
			return;
		}

		/**
		 * [_vasba_basic_auth description]
		 * @link http://php.net/manual/ja/features.http-auth.php
		 * @return null
		 */
		function _vasba_basic_auth() {
			$auth_user = ( isset( $_SERVER['PHP_AUTH_USER'] ) ) ? $_SERVER['PHP_AUTH_USER'] : '';
			$auth_pw   = ( isset( $_SERVER['PHP_AUTH_PW'] ) ) ? $_SERVER['PHP_AUTH_PW'] : '';
			if ( empty( $auth_user )
				 && empty( $auth_pw )
				 && isset( $_SERVER['HTTP_AUTHORIZATION'] )
				 && preg_match( '/Basic\s+(.*)\z/i', $_SERVER['HTTP_AUTHORIZATION'], $matches )
			) {
				list( $auth_user, $auth_pw ) = explode( ':', base64_decode( $matches[1] ) );
				$auth_user                   = strip_tags( $auth_user );
				$auth_pw                     = strip_tags( $auth_pw );
			}

			nocache_headers();

			if ( is_user_logged_in() || ! is_wp_error( wp_authenticate( $auth_user, $auth_pw ) ) ) {
				return;
			}
			header( 'WWW-Authenticate: Basic realm="Private Page"' );
			header( 'HTTP/1.0 401 Unauthorized' );
			die( __( 'Authorization Required.', VA_SIMPLE_BASIC_AUTH_TEXTDOMAIN ) );
		}
	}
	new VA_SIMPLE_BASIC_AUTH;
endif;
