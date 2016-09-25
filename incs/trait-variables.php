<?php
/**
 * WordPress plugin variable class.
 *
 * @package    WordPress
 * @subpackage VA Simple Basic Auth
 * @author     KUCKLU <kuck1u@visualive.jp>
 *             Copyright (C) 2016 KUCKLU and VisuAlive.
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
	 * Class Variable
	 *
	 * @package VASIMPLEBASICAUTH\Modules
	 */
	trait Variable {

		/**
		 * Get psth the htaccess file.
		 *
		 * @return string
		 */
		public static function get_path_htaccess() {
			return apply_filters( 'va_simple_basic_auth_path_htaccess', ABSPATH . '.htaccess' );
		}

		/**
		 * Get mod_rewrite.
		 *
		 * @return array
		 */
		public static function get_mod_rewrite() {
			$mod_rewrite = <<< EOM
# BEGIN VA SIMPLE BASIC AUTH
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule ^(.*) - [E=HTTP_AUTHORIZATION:%1]
</IfModule>
# END VA SIMPLE BASIC AUTH

EOM;

			return apply_filters( 'va_simple_basic_auth_mod_rewrite', $mod_rewrite );
		}
	}
}
