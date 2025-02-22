<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished  to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */

	/*
		Buat function https manual
		Alhadi Rahman
		08 Okt 2019

	*/

date_default_timezone_set("Asia/Jakarta");
//setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
setlocale(LC_ALL, "en_US.UTF-8");

	if (!function_exists('getallheaders')) {
	    function getallheaders() {
	    $headers = [];
	    foreach ($_SERVER as $name => $value) {
	        if (substr($name, 0, 5) == 'HTTP_') {
	            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
	        }
	    }
	    return $headers;
	    }
	}

	function is_https_chek()
	{
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
		{
			return TRUE;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
		{
			return TRUE;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return TRUE;
		}

		return FALSE;			
	}

	$HostPath=(is_https_chek() ? "https://" : "http://");

	stream_context_set_default([
		'ssl' => [
			'verify_peer' => false,
			'verify_peer_name' => false,
		],
	]);
	/* End Function https */

	// define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
	$ServerName = $_SERVER['SERVER_NAME'];
	define("URLAD","http://10.1.30.2:8076/", true);
	define("url_files","https://"."files.podomorouniversity.ac.id/", true);
	define("url_DocxToPDf","http://10.1.10.31/apidocxtopdf/", true); // my local adhi
	// define("url_DocxToPDf","http://10.1.30.33/docxtopdf/", true);

	/*
		Set Environtment if not exist, ref by docker instalation
		Alhadi Rahman 2021 - 01 - 25
	*/
		define('_HOST_ID', isset($_SERVER['_HOST_ID']) ? $_SERVER['_HOST_ID'] : 'DEMO');

		if (isset($_SERVER['_HOST_ID'])) {
			if (_HOST_ID == 'DEMO') {
				define('_DB_HOST', isset($_SERVER['_DB_HOST']) ? $_SERVER['_DB_HOST'] : '10.1.30.59');
			}
			else
			{
				define('_DB_HOST', isset($_SERVER['_DB_HOST']) ? $_SERVER['_DB_HOST'] : '10.1.30.18');
			}
		}
		else
		{
			define('_DB_HOST', isset($_SERVER['_DB_HOST']) ? $_SERVER['_DB_HOST'] : '10.1.30.18');
		}
		

		define('_DB_USER', isset($_SERVER['_DB_USER']) ? $_SERVER['_DB_USER'] : 'db_itpu');
		define('_DB_PASSWORD', isset($_SERVER['_DB_PASSWORD']) ? $_SERVER['_DB_PASSWORD'] : 'Uap)(*&^%');
		define('_DB_NAME', isset($_SERVER['_DB_NAME']) ? $_SERVER['_DB_NAME'] : 'db_academic');
		define('_DB_PORT', isset($_SERVER['_DB_PORT']) ? $_SERVER['_DB_PORT'] : '3306');
	
	switch ($ServerName) {
		case 'pcam.podomorouniversity.ac.id':
		    define("url_registration",$HostPath."admission.podomorouniversity.ac.id/", true);
            define("serverRoot",$HostPath."pcam.podomorouniversity.ac.id", true);
            define("url_pas",$HostPath."pcam.podomorouniversity.ac.id/", true);
            define("url_img_employees",url_pas."uploads/employees/", true);
            define("url_img_students",url_pas."uploads/students/", true);

            define("url_pcam",$HostPath."pcam.podomorouniversity.ac.id/dashboard", true);
            define("url_students",$HostPath."studentpu.podomorouniversity.ac.id/home", true);
            define("url_lecturers",$HostPath."lecturerpu.podomorouniversity.ac.id/home", true);
            define("url_sign_out",$HostPath."portal.podomorouniversity.ac.id/", true);
    //
            define("url_sign_in_lecturers",$HostPath."lecturerpu.podomorouniversity.ac.id/", true);
            define("url_sign_in_students",$HostPath."studentpu.podomorouniversity.ac.id/", true);
            define("url_library","http://library.podomorouniversity.ac.id/", true);
            define("url_blogadmin",$HostPath."adminblogs.podomorouniversity.ac.id/", true);
            
            if (isset($_SERVER['_DB_HOST'])) {
            	define("path_register_online","/home/docker1/admission/", true);
            }
            else
            {
            	define("path_register_online","/var/www/html/registeronline/", true);
            }
            
            define('ENVIRONMENT', 'production',true);
			break;
		case 'demopcam.podomorouniversity.ac.id':
		    define("url_registration",$HostPath."demoadmission.podomorouniversity.ac.id/", true);
            define("serverRoot",$HostPath."demopcam.podomorouniversity.ac.id", true);
            define("url_pas",$HostPath."demopcam.podomorouniversity.ac.id/", true);
            define("url_img_employees",url_pas."uploads/employees/", true);
            define("url_img_students",url_pas."uploads/students/", true);

            define("url_pcam",$HostPath."demopcam.podomorouniversity.ac.id/dashboard", true);
            define("url_students",$HostPath."demostudentpu.podomorouniversity.ac.id/home", true);
            define("url_lecturers",$HostPath."demolecturerpu.podomorouniversity.ac.id/home", true);
            define("url_sign_out",$HostPath."demoportal.podomorouniversity.ac.id/", true);
    //
            define("url_sign_in_lecturers",$HostPath."demolecturerpu.podomorouniversity.ac.id/", true);
            define("url_sign_in_students",$HostPath."demostudentpu.podomorouniversity.ac.id/", true);
            define("url_library","http://library.podomorouniversity.ac.id/", true);
            define("url_blogadmin",$HostPath."demoadminblogs.podomorouniversity.ac.id/", true);
            
            if (isset($_SERVER['_DB_HOST'])) {
            	define("path_register_online","/home/docker1/admission/", true);
            }
            else
            {
            	define("path_register_online","/var/www/html/registeronline/", true);
            }
            define('ENVIRONMENT', 'development',true);
			break;		
		default:
            $port_user = ($_SERVER['SERVER_PORT']!='80') ? ':'.$_SERVER['SERVER_PORT'] : '';
            $folder_user = 'puis';
            $portal_user = 'portal';
            define("port",$port_user, true);

            // Local Nandang
            define("url_registration",$HostPath."localhost/registeronline/", true);
            define("serverRoot",$HostPath."localhost".port."/".$folder_user, true);
            define("url_pas",$HostPath."localhost".port."/".$folder_user."/", true);
            define("url_img_employees",url_pas."uploads/employees/", true);
            define("url_img_students",url_pas."uploads/students/", true);

            define("url_pcam",url_pas."dashboard", true);

            define("url_sign_out",$HostPath."localhost".port."/".$portal_user."/", true);

            // Auth From PCAM
            define("url_sign_in_lecturers",$HostPath."localhost".port."/lecturer/", true);
            define("url_sign_in_students",$HostPath."localhost".port."/students/", true);
            define("url_library","http://library.podomorouniversity.ac.id/", true);
            define("url_blogadmin",$HostPath."localhost/blogscms/", true);

            define("url_lecturers",url_sign_in_lecturers."home", true);
            define("url_students",url_sign_in_students."home", true);
            if (isset($_SERVER['_DB_HOST'])) {
            	define("path_register_online","/home/docker1/admission/", true);
            }
            else
            {
            	 define("path_register_online","c:/xampp/htdocs/registeronline/", true);
            }

            define('ENVIRONMENT', 'development',true);
			break;
	}



/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 */
	$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 */
	$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * NO TRAILING SLASH!
 */
	$view_folder = '';


/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here. For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT: If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller. Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 */
	// The directory name, relative to the "controllers" directory.  Leave blank
	// if your controller is not in a sub-directory within the "controllers" one
	// $routing['directory'] = '';

	// The controller class file name.  Example:  mycontroller
	// $routing['controller'] = '';

	// The controller function you wish to be called.
	// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 */
	// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (($_temp = realpath($system_path)) !== FALSE)
	{
		$system_path = $_temp.DIRECTORY_SEPARATOR;
	}
	else
	{
		// Ensure there's a trailing slash
		$system_path = strtr(
			rtrim($system_path, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		).DIRECTORY_SEPARATOR;
	}

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
		exit(3); // EXIT_CONFIG
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// Path to the system directory
	define('BASEPATH', $system_path);

	// Path to the front controller (this file) directory
	define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

	// Name of the "system" directory
	define('SYSDIR', basename(BASEPATH));

	// The path to the "application" directory
	if (is_dir($application_folder))
	{
		if (($_temp = realpath($application_folder)) !== FALSE)
		{
			$application_folder = $_temp;
		}
		else
		{
			$application_folder = strtr(
				rtrim($application_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
	{
		$application_folder = BASEPATH.strtr(
			trim($application_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG
	}

	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);

	// The path to the "views" directory
	if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.'views';
	}
	elseif (is_dir($view_folder))
	{
		if (($_temp = realpath($view_folder)) !== FALSE)
		{
			$view_folder = $_temp;
		}
		else
		{
			$view_folder = strtr(
				rtrim($view_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.strtr(
			trim($view_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG
	}

	define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 */
require_once BASEPATH.'core/CodeIgniter.php';
