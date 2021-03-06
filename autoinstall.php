<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Classifieds Plugin 1.3.0                                                  |
// +---------------------------------------------------------------------------+
// | autoinstall.php                                                           |
// |                                                                           |
// | This file provides helper functions for the automatic plugin install.     |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2014 by the following authors:                              |
// |                                                                           |
// | Authors: ::Ben - cordiste AT free DOT fr                                  |
// +---------------------------------------------------------------------------+
// | Created with the Geeklog Plugin Toolkit.                                  |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

/**
* @package Classifieds
*/

/**
* Plugin autoinstall function
*
* @param    string  $pi_name    Plugin name
* @return   array               Plugin information
*
*/
function plugin_autoinstall_classifieds($pi_name)
{
    $pi_name         = 'classifieds';
    $pi_display_name = 'Classifieds';
    $pi_admin        = $pi_display_name . ' Admin';
	$pi_users        = $pi_display_name . ' Users';

    $info = array(
        'pi_name'         => $pi_name,
        'pi_display_name' => $pi_display_name,
        'pi_version'      => '1.3.2',
        'pi_gl_version'   => '1.8.0',
        'pi_homepage'     => 'http://geeklog.fr'
    );

    $groups = array(
        $pi_admin => 'Users in this group can administer the '
                     . $pi_display_name . ' plugin',
		$pi_users => 'Users in this group posted at least one ad'
    );

    $features = array(
        $pi_name . '.admin'    => 'Full access to ' . $pi_display_name
                                  . ' plugin',
		$pi_name . '.publish'  => 'Can publish classifieds'
    );

    $mappings = array(
        $pi_name . '.admin'     => array($pi_admin)
    );

    $tables = array(
        'cl',
		'cl_cat',
		'cl_pic',
		'cl_users'
    );

    $inst_parms = array(
        'info'      => $info,
        'groups'    => $groups,
        'features'  => $features,
        'mappings'  => $mappings,
        'tables'    => $tables
    );

    return $inst_parms;
}

/**
* Check if the plugin is compatible with this Geeklog version
*
* @param    string  $pi_name    Plugin name
* @return   boolean             true: plugin compatible; false: not compatible
*
*/
function plugin_compatible_with_this_version_classifieds($pi_name)
{
    global $_CONF, $_DB_dbms;

    // check if we support the DBMS the site is running on
    $dbFile = $_CONF['path'] . 'plugins/' . $pi_name . '/sql/'
            . $_DB_dbms . '_install.php';
    if (! file_exists($dbFile)) {
        return false;
    }

    // add checks here

    return true;
}

function plugin_load_configuration_classifieds($pi_name)
{
    global $_CONF;

    $base_path = $_CONF['path'] . 'plugins/' . $pi_name . '/';

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $base_path . 'install_defaults.php';

    return plugin_initconfig_classifieds();
}

function plugin_postinstall_classifieds($pi_name)
{
    global $_TABLES, $_CONF, $_USER;
	
	/* This code is for statistics ONLY */
    $message =  'Completed classifieds plugin install: ' .date('m d Y',time()) . "   AT " . date('H:i', time()) . "\n";
    $message .= 'Site: ' . $_CONF['site_url'] . ' and Sitename: ' . $_CONF['site_name'] . "\n";
	if (function_exists('CLASSIFIEDS_adCopy')) $message .= 'Proversion' . "\n";
    $pi_version = DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name = 'classifieds'");
    COM_mail("ben@geeklog.fr","$pi_name Version:$pi_version Install successfull",$message);

	return true;
}
?>
