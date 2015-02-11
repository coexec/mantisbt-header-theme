<?php
# MantisBT - a php based bugtracking system
# Copyright (C) 2002 - 2014  MantisBT Team - mantisbt-dev@lists.sourceforge.net
# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

require_once( config_get( 'class_path' ) . 'MantisPlugin.class.php' );

class MantisHeaderThemePlugin extends MantisPlugin  {

	/**
	 *  A method that populates the plugin information and minimum requirements.
	 */
	function register( ) {
		$this->name = lang_get( 'plugin_header_theme' );
		$this->description = lang_get( 'plugin_header_theme_description' );

		$this->page = 'login';

		$this->version = '1.0';
		$this->requires = array(
			'MantisCore' => '1.2.0',
		);

		$this->author = 'Kay Stenschke';
		$this->contact = 'kstenschke@coexec.com';
		$this->url = 'http://www.coexec.com';
	}

	/**
	 * Default plugin configuration.
	 */
	function config() {
		return array(
//			'eczlibrary' => ON,
		);
	}

	/**
	 * Init - set include path
	 */
	function init() {
		spl_autoload_register( array( 'MantisHeaderThemePlugin', 'autoload' ) );
		
		$t_path = config_get_global('plugin_path' ). plugin_get_current() . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR;

		set_include_path(get_include_path() . PATH_SEPARATOR . $t_path);
	}

	/**
	 * Autoload ezcBase
	 *
	 * @param $className
	 * @throws ezcBaseAutoloadException
	 */
	public static function autoload( $className ) {
		if (class_exists( 'ezcBase' ) ) {
			ezcBase::autoload( $className );
		}
	}
	
	function hooks( ) {
		return array(
			'EVENT_LAYOUT_RESOURCES'		=> 'menu_main',
		);
	}

	/**
	 * Inject (vanilla) JavaScript to update DOM with customized header logo image.
	 *
	 * @return	string
	 */
	function menu_main() {
		return '
<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function(){
		var urlParts = document.location.pathname.replace("_page.php","").split("/");
		var pageIdentifier = urlParts[ urlParts.length-1 ];
		var logoSrc;
		switch(pageIdentifier) {
			case \'login\':	// Login page only
				logoSrc = \'plugins/MantisHeaderTheme/files/images/logo-header-login.png\';
				break;
			default: // Page header when logged in
				logoSrc = \'plugins/MantisHeaderTheme/files/images/logo-header.png\';
		}
		var topLogo = document.querySelectorAll(\'img[src$="mantis_logo.png"]\')[0];
		topLogo.src = logoSrc;
	});

</script>';

	}

}

function mantisheadertheme_autoload() {

}
