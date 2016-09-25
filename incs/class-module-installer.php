<?php
/**
 * WordPress plugin installer class.
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
	 * Class Installer
	 *
	 * @package VASIMPLEBASICAUTH\Modules
	 */
	class Installer {
		use Variable;

		/**
		 * Get my class.
		 *
		 * @return string
		 */
		public static function get_called_class() {
			return get_called_class();
		}

		/**
		 * Install.
		 *
		 * @return void
		 */
		public static function install() {
			self::_file_put_htaccess( 'install' );

			do_action( 'va_simple_basic_auth_install' );
		}

		/**
		 * Uninstall.
		 *
		 * @return void
		 */
		public static function uninstall() {
			self::_file_put_htaccess( 'uninstall' );

			do_action( 'va_simple_basic_auth_uninstall' );
		}

		/**
		 * Update.
		 *
		 * @return void
		 */
		public static function update() {
			do_action( 'va_simple_basic_auth_update' );
		}

		/**
		 * Checks whether a htaccess file exists.
		 *
		 * @return bool
		 */
		protected static function _exists_htaccess() {
			return file_exists( self::get_path_htaccess() );
		}

		/**
		 * Reads entire htaccess file into a string.
		 *
		 * @return bool|string
		 */
		protected static function _file_get_htaccess() {
			$content = self::_exists_htaccess();

			if ( true === $content ) {
				$content = file_get_contents( self::get_path_htaccess() );
			}

			return $content;
		}

		/**
		 * Find the position of the first occurrence of a substring in a string.
		 *
		 * @return array|bool
		 */
		protected static function _strpos_htaccess() {
			$content     = self::_file_get_htaccess();
			$mod_rewrite = self::get_mod_rewrite();
			$return      = false;

			if ( false !== strpos( $content, $mod_rewrite ) ) {
				$return = true;
			}

			return $return;
		}

		/**
		 * Get htaccess info.
		 *
		 * @return array|bool
		 */
		protected static function _get_htaccess() {
			$content     = self::_file_get_htaccess();
			$mod_rewrite = self::get_mod_rewrite();
			$return      = false;

			if ( false !== $content ) {
				$return = [
					'content'     => $content,
					'mod_rewrite' => $mod_rewrite,
				];
			}

			return $return;
		}

		/**
		 * Write a string to a htaccess file.
		 *
		 * @param string $action install or uninstall.
		 */
		protected static function _file_put_htaccess( $action = '' ) {
			$file   = self::_get_htaccess();
			$strpos = self::_strpos_htaccess();

			if ( false === $file || ( ! is_array( $file ) && empty( $file ) ) ) {
				return;
			}

			switch ( $action ) {
				case 'install':
					if ( false === $strpos ) {
						file_put_contents(
							self::get_path_htaccess(),
							str_replace( '# BEGIN WordPress', $file['mod_rewrite'] . "\n# BEGIN WordPress", $file['content'] )
						);
					}
					break;
				case 'uninstall':
					if ( true === $strpos ) {
						file_put_contents(
							self::get_path_htaccess(),
							str_replace( $file['mod_rewrite'], '', $file['content'] )
						);
					}
					break;
			}
		}
	}
}
