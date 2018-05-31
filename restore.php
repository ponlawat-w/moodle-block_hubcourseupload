<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/classes/courseupload_form.php');
require_once(__DIR__ . '/../../backup/util/includes/restore_includes.php');

$courseuploadform = new courseupload_form();
if (!$courseuploadform->is_submitted()) {
    throw new Exception(get_string('error_filenotuploaded', 'block_hubcourseupload'));
}

$context = context_user::instance($USER->id);

require_capability('block/hubcourseupload:upload', $context);

$courseuploaddata = $courseuploadform->get_data();

$fs = get_file_storage();
$file = $fs->get_file($context->id, 'user', 'draft', $courseuploaddata->coursefile, '/');
if (!$file) {
    throw new Exception(get_string('error_filenotuploaded', 'block_hubcourseupload'));
}

$filename = restore_controller::get_tempdir_name(0, $USER->id);
$pathname = $CFG->tempdir . '/backup/' . $filename;
if (!$courseuploadform->save_file('coursefile', $pathname)) {
    throw new Exception(get_string('error_cannotsaveuploadfile', 'block_hubcourseupload'));
}

$restoreurl = new moodle_url('/backup/restore.php', [
    'contextid' => context_system::instance()->id,
    'filename' => $filename
]);

redirect($restoreurl);