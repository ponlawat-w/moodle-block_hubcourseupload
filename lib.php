<?php
const BLOCK_HUBCOURSEUPLOAD_STEP_PREPARE = 0;
const BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED = 1;
const BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED = 2;

function block_hubcourseupload_infoblockenabled()
{
    global $BLOCK_HUBCOURSEUPLOAD_INFOENABLED;

    if (!isset($BLOCK_HUBCOURSEUPLOAD_INFOENABLED)) {
        $blocks = core_plugin_manager::instance()->get_enabled_plugins('block');
        $BLOCK_HUBCOURSEUPLOAD_INFOENABLED = in_array('hubcourseinfo', $blocks);
    }

    return $BLOCK_HUBCOURSEUPLOAD_INFOENABLED;
}

function block_hubcourseupload_getmaxfilesize()
{
    $generalmaximum = get_max_upload_file_size();

    if (block_hubcourseupload_infoblockenabled()) {
        $infosettings = get_config('block_hubcourseupload', 'maxfilesize') * 1024 * 1024;

        if ($infosettings > 0) {
            return $generalmaximum < $infosettings ? $generalmaximum : $infosettings;
        }
    }

    return $generalmaximum;
}

function block_hubcourseupload_getroleid()
{
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

function block_hubcourseupload_getsubdirectories($path)
{
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

function block_hubcourseupload_getplugins($extractedpath)
{
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

function block_hubcourseupload_valid($plugins)
{
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

function block_hubcourseupload_plugininfotable($plugins)
{
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

function block_hubcourseupload_plugininfotable_html($table)
{
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

function block_hubcourseupload_reduceinfo($info) {
    $newinfo = new stdClass();
    $newinfo->type = $info->type;
    $newinfo->moodle_version = $info->moodle_version;
    $newinfo->moodle_release = $info->moodle_release;
    $newinfo->original_wwwroot = $info->original_wwwroot;
    $newinfo->original_course_id = $info->original_course_id;

    return $newinfo;
}

function block_hubcourseupload_getbackuppath($filename) {
    global $CFG;
    return $CFG->tempdir . '/backup/' . $filename;
}