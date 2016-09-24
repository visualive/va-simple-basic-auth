<?php
/**
 * WordPress plugin defines.
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

$va_simplebasicauth_path = str_replace( 'incs/defines.php', 'va-simple-basic-auth.php', __FILE__ );
$va_simplebasicauth_data = get_file_data( $va_simplebasicauth_path, array(
	'Name'             => 'Plugin Name',
	'PluginURI'        => 'Plugin URI',
	'Version'          => 'Version',
	'WordPressVersion' => 'WordPress Version',
	'PHPVersion'       => 'PHP Version',
	'Description'      => 'Description',
	'Author'           => 'Author',
	'AuthorURI'        => 'Author URI',
	'TextDomain'       => 'Text Domain',
	'DomainPath'       => 'Domain Path',
	'Prefix'           => 'Prefix',
	'Network'          => 'Network',
) );

define( 'VA_SIMPLEBASICAUTH_URL', plugin_dir_url( $va_simplebasicauth_path ) );
define( 'VA_SIMPLEBASICAUTH_PATH', plugin_dir_path( $va_simplebasicauth_path ) );
define( 'VA_SIMPLEBASICAUTH_BASENAME', dirname( plugin_basename( $va_simplebasicauth_path ) ) );
define( 'VA_SIMPLEBASICAUTH_NAME', $va_simplebasicauth_data['Name'] );
define( 'VA_SIMPLEBASICAUTH_VERSION', $va_simplebasicauth_data['Version'] );
define( 'VA_SIMPLEBASICAUTH_VERSION_WP', $va_simplebasicauth_data['WordPressVersion'] );
define( 'VA_SIMPLEBASICAUTH_VERSION_PHP', $va_simplebasicauth_data['PHPVersion'] );

unset( $va_simplebasicauth_path );
unset( $va_simplebasicauth_data );
