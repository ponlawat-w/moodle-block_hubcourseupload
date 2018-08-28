<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Functions libraries
 *
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Step of extracting file information
const BLOCK_HUBCOURSEUPLOAD_STEP_PREPARE = 0;

// Step of when there is no site version difference, or the difference has been accepted
const BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED = 1;

// Step of when there is no plugin version difference, or the difference has been accepted
const BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED = 2;

/**
 * Check if block_hubcourseinfo is enabled in this site
 * @return bool
 */
function block_hubcourseupload_infoblockenabled() {
    global $BLOCK_HUBCOURSEUPLOAD_INFOENABLED;

    if (!isset($BLOCK_HUBCOURSEUPLOAD_INFOENABLED)) {
        $blocks = core_plugin_manager::instance()->get_enabled_plugins('block');
        $BLOCK_HUBCOURSEUPLOAD_INFOENABLED = in_array('hubcourseinfo', $blocks);
    }

    return $BLOCK_HUBCOURSEUPLOAD_INFOENABLED;
}

/**
 * Get maximum file size
 * @return float|int
 * @throws dml_exception
 */
function block_hubcourseupload_getmaxfilesize() {
    $generalmaximum = get_max_upload_file_size();

    if (block_hubcourseupload_infoblockenabled()) {
        $infosettings = get_config('block_hubcourseupload', 'maxfilesize') * 1024 * 1024;

        if ($infosettings > 0) {
            return $generalmaximum < $infosettings ? $generalmaximum : $infosettings;
        }
    }

    return $generalmaximum;
}

/**
 * Get role ID
 * @return int|null
 * @throws coding_exception
 * @throws dml_exception
 */
function block_hubcourseupload_getroleid() {
    global $DB;

    if (!get_config('block_hubcourseupload', 'allowcapabilitychange')) {
        return null;
    }

    $role = $DB->get_record('role', ['shortname' => 'hubcourseupload_user']);
    if (!$role) {
        return create_role('Course Uploader', 'hubcourseupload_user', 'User for hub course upload');
    }

    return $role->id;
}

/**
 * Get sub-directories of given path
 * @param string $path
 * @return string[]
 */
function block_hubcourseupload_getsubdirectories($path) {
    if (!is_dir($path)) {
        return [];
    }

    if (!$dir = opendir($path)) {
        return [];
    }

    $dirs = [];

    while ($file = readdir($dir)) {
        if ($file == '.' || $file == '..') {
            continue;
        }

        if (is_dir($path . '/' . $file)) {
            $dirs[] = $path . '/' . $file;
        }
    }

    return $dirs;
}

/**
 * Get plugins information from extracted path
 * @param string $extractedpath
 * @return array
 */
function block_hubcourseupload_getplugins($extractedpath) {
    $result = [
        'mod' => [],
        'blocks' => []
    ];

    $moddirs = block_hubcourseupload_getsubdirectories($extractedpath . '/activities');
    foreach ($moddirs as $moddir) {
        $modpath = $moddir . '/module.xml';
        $xml = simplexml_load_file($modpath);
        if (!$xml || !isset($xml->modulename)) {
            continue;
        }
        $modname = (string)$xml->modulename;
        $version = isset($xml['version']) ? (double)$xml['version'] : 0;

        if (!isset($result['mod'][$modname])) {
            $result['mod'][$modname] = $version;
        }
    }

    $blockdirs = block_hubcourseupload_getsubdirectories($extractedpath . '/course/blocks');
    foreach ($blockdirs as $blockdir) {
        $blockpath = $blockdir . '/block.xml';
        $xml = simplexml_load_file($blockpath);
        if (!$xml || !isset($xml->blockname)) {
            continue;
        }
        $blockname = (string)$xml->blockname;
        $version = isset($xml['version']) ? (double)$xml['version'] : 0;

        if (!isset($result['blocks'][$blockname])) {
            $result['blocks'][$blockname] = $version;
        }
    }

    return $result;
}

/**
 * Check if all plugin dependencies indicated in mbz file is valid in this site
 * @param array $plugins
 * @return bool
 */
function block_hubcourseupload_valid($plugins) {
    $installedmods = core_plugin_manager::instance()->get_plugins_of_type('mod');
    foreach ($plugins['mod'] as $modname => $version) {
        if (!isset($installedmods[$modname]) || $installedmods[$modname]->versiondb != $version) {
            return false;
        }
    }

    $installedblocks = core_plugin_manager::instance()->get_plugins_of_type('block');
    foreach ($plugins['blocks'] as $blockname => $version) {
        if (!isset($installedblocks[$blockname]) || $installedblocks[$blockname]->versiondb != $version) {
            return false;
        }
    }

    return true;
}

/**
 * Create table array informing plugin difference data
 * @param array $plugins
 * @return array
 */
function block_hubcourseupload_plugininfotable($plugins) {
    //table[pluginname] = [courseversion=>?, siteversion=?]
    $table = [];

    $installedmods = core_plugin_manager::instance()->get_plugins_of_type('mod');
    $installedblocks = core_plugin_manager::instance()->get_plugins_of_type('block');

    foreach ($plugins['mod'] as $modname => $version) {
        $table['mod_' . $modname] = [
            'courseversion' => $version,
            'siteversion' => isset($installedmods[$modname]) ? $installedmods[$modname]->versiondb : 0
        ];
    }

    foreach ($plugins['blocks'] as $blockname => $version) {
        $table['block_' . $blockname] = [
            'courseversion' => $version,
            'siteversion' => isset($installedblocks[$blockname]) ? $installedblocks[$blockname]->versiondb : 0
        ];
    }

    return $table;
}

/**
 * Get HTML table from table array for page rendering
 * @param array $table
 * @return html_table
 * @throws coding_exception
 */
function block_hubcourseupload_plugininfotable_html($table) {
    $htmltable = new html_table();
    $htmltable->head = [
        get_string('requiredplugin_name', 'block_hubcourseupload'),
        get_string('requiredplugin_courseversion', 'block_hubcourseupload'),
        get_string('requiredplugin_siteversion', 'block_hubcourseupload'),
        get_string('requiredplugin_status', 'block_hubcourseupload')
    ];
    $htmltable->data = [];
    foreach ($table as $pluginname => $versiondata) {
        $text = '';
        $style = 'default';
        if (!$versiondata['siteversion']) {
            $text = get_string('requiredplugin_notinstalled', 'block_hubcourseupload');
            $style = 'danger';
        } else if ($versiondata['siteversion'] == $versiondata['courseversion']) {
            $text = get_string('requiredplugin_identical', 'block_hubcourseupload');
            $style = 'success';
        } else if ($versiondata['siteversion'] < $versiondata['courseversion']) {
            $text = get_string('requiredplugin_siteolder', 'block_hubcourseupload');
            $style = 'warning';
        } else if ($versiondata['siteversion'] > $versiondata['courseversion']) {
            $text = get_string('requiredplugin_sitenewer', 'block_hubcourseupload');
            $style = 'success';
        }

        $htmltable->data[] = [
            $pluginname,
            $versiondata['courseversion'],
            $versiondata['siteversion'] ? $versiondata['siteversion'] : '',
            html_writer::span($text, 'text-' . $style)
        ];
    }

    return $htmltable;
}

/**
 * Reduce info object by removing unnecessary
 * @param stdClass $info
 * @return stdClass
 */
function block_hubcourseupload_reduceinfo($info) {
    $newinfo = new stdClass();
    $newinfo->type = $info->type;
    $newinfo->moodle_version = $info->moodle_version;
    $newinfo->moodle_release = $info->moodle_release;
    $newinfo->original_wwwroot = $info->original_wwwroot;
    $newinfo->original_course_id = $info->original_course_id;

    return $newinfo;
}

/**
 * Get backup path
 * @param string $filename
 * @return string
 */
function block_hubcourseupload_getbackuppath($filename) {
    global $CFG;
    return $CFG->tempdir . '/backup/' . $filename;
}
