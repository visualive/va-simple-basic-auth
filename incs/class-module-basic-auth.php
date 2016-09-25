<?php
/**
 * WordPress plugin Basic Auth class.
 *
 * @package    WordPress
 * @subpackage VA Simple Basic Auth
 * @author     KUCKLU <kuck1u@visualive.jp>
 *             Copyright (C) 2015 KUCKLU and VisuAlive.
 *             This program is free software; you can redistribute it and/or modify
 *             it under the terms of the GNU General Public License as published by
 *             the Free Software Foundation; either version 2 of the License, or
 *             (at your option) any later version.
 *             This program is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License along
 *             with this program; if not, write to the Free Software Foundation, Inc.,
 *             51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *             It is also available through the world-wide-web at this URL:
 *             http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace VASIMPLEBASICAUTH\Modules {
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Class BasicAuth
	 *
	 * @package VASIMPLEBASICAUTH\Modules
	 */
	class BasicAuth {
		use Instance;

		/**
		 * Get my class.
		 *
		 * @return string
		 */
		public static function get_called_class() {
			return get_called_class();
		}

		/**
		 * This hook is called once any activated plugins have been loaded.
		 */
		private function __construct() {
			add_action( 'login_init', [ &$this, 'basic_auth' ], 0 );
		}

		/**
		 * Run Basic Auth.
		 */
		public function basic_auth() {
			$server    = $_SERVER;
			$auth_user = isset( $server['PHP_AUTH_USER'] ) ? $server['PHP_AUTH_USER'] : '';
			$auth_pw   = isset( $server['PHP_AUTH_PW'] ) ? $server['PHP_AUTH_PW'] : '';

			if ( empty( $auth_user )
			     && empty( $auth_pw )
			     && isset( $server['HTTP_AUTHORIZATION'] )
			     && preg_match( '/Basic\s+(.*)\z/i', $server['HTTP_AUTHORIZATION'], $matches )
			) {
				list( $auth_user, $auth_pw ) = explode( ':', base64_decode( $matches[1] ) );
			}

			$auth_user = wp_strip_all_tags( wp_unslash( $auth_user ) );
			$auth_pw   = wp_strip_all_tags( wp_unslash( $auth_pw ) );

			nocache_headers();

			if ( is_user_logged_in() || ! is_wp_error( wp_authenticate( $auth_user, $auth_pw ) ) ) {
				return;
			}

			self::_authenticate();
		}

		/**
		 * Authenticate.
		 */
		protected function _authenticate() {
			$error_title = __( 'Authorization Required.', 'va-simple-basic-auth' );

			header( 'WWW-Authenticate: Basic realm="Private Page"' );
			header( 'HTTP/1.0 401 Unauthorized' );

			die( esc_html( $error_title ) );
		}
	}
}
