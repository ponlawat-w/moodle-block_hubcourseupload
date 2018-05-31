<?php
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