<?php
/**
 * WordPress plugin functions.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

load_plugin_textdomain( 'va-simple-basic-auth', false, VA_SIMPLEBASICAUTH_BASENAME . '/langs' );

/**
 * Version check.
 *
 * @return bool
 */
function va_simplebasicauth_version_check() {
	$result = false;

	if ( version_compare( $GLOBALS['wp_version'], VA_SIMPLEBASICAUTH_VERSION_WP, '>=' ) && version_compare( PHP_VERSION, VA_SIMPLEBASICAUTH_VERSION_PHP, '>=' ) ) {
		$result = true;
	}

	return $result;
}
