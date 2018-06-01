<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/classes/courseupload_form.php');
require_once(__DIR__ . '/../../backup/util/includes/restore_includes.php');

if (block_hubcourseupload_infoblockenabled()) {
    require_once(__DIR__ . '/../../blocks/hubcourseinfo/lib.php');
}

$courseuploadform = new courseupload_form();
if (!$courseuploadform->is_submitted()) {
    throw new Exception(get_string('error_filenotuploaded', 'block_hubcourseupload'));
}

$systemcontext = context_system::instance();
$usercontext = context_user::instance($USER->id);

require_capability('block/hubcourseupload:upload', $usercontext);

$courseuploaddata = $courseuploadform->get_data();

$filename = restore_controller::get_tempdir_name(0, $USER->id);
$archivepath = $CFG->tempdir . '/backup/' . $filename;
if (!$courseuploadform->save_file('coursefile', $archivepath)) {
    throw new Exception(get_string('error_cannotsaveuploadfile', 'block_hubcourseupload'));
}

$info = backup_general_helper::get_backup_information_from_mbz($archivepath);
if ($info->type != 'course') {
    fulldelete($archivepath);
    throw new Exception(get_string('error_backupisnotcourse', 'block_hubcourseupload'));
}

raise_memory_limit(MEMORY_EXTRA);

$extractedname = restore_controller::get_tempdir_name($systemcontext->id, $USER->id);
$extractedpath = $CFG->tempdir . '/backup/' . $extractedname . '/';
$fb = get_file_packer('application/vnd.moodle.backup');
if (!$fb->extract_to_pathname($archivepath, $extractedpath, null)) {
    throw new Exception(get_string('error_cannotextractfile', 'block_hubcourseupload'));
}

list($fullname, $shortname) = restore_dbops::calculate_course_names(0, get_string('restoringcourse', 'backup'), get_string('restoringcourseshortname', 'backup'));
$courseid = restore_dbops::create_new_course($fullname, $shortname, 1);
$course = $DB->get_record('course', ['id' => $courseid]);

try {
    $coursecontext = context_course::instance($courseid);

    if (!has_capability('moodle/restore:restorecourse', $coursecontext)) {
        $roleid = block_hubcourseupload_getroleid();
        if (!$roleid) {
            throw new Exception(get_string('error_cannotgetroleinfo', 'block_hubcourseupload'));
        }

        role_assign($roleid, $USER->id, $coursecontext->id);
        assign_capability('moodle/restore:restorecourse', CAP_ALLOW, $roleid, $coursecontext->id, true);
        $coursecontext->mark_dirty();
    }

    $rc = new restore_controller($extractedname, $courseid, backup::INTERACTIVE_NO, backup::MODE_GENERAL, $USER->id, backup::TARGET_NEW_COURSE);
    $rc->set_status(backup::STATUS_AWAITING);
    $rc->get_plan()->execute();

    $blocks = backup_general_helper::get_blocks_from_path($extractedpath . '/course');

    $rc->destroy();

    if (block_hubcourseupload_infoblockenabled()) {
        $hubcourse = block_hubcourseinfo_gethubcoursefromcourseid($courseid);
        $hubcourse->demourl = $info->original_wwwroot . '/course/view.php?id=' . $info->original_course_id;

        if ($hubcourse) {
            $version = new stdClass();
            $version->id = 0;
            $version->hubcourseid = $hubcourse->id;
            $version->moodleversion = $info->moodle_version;
            $version->description = get_string('initialversion', 'block_hubcourseupload');
            $version->userid = $USER->id;
            $version->timeuploaded = time();
            $version->fileid = 0;
            $versionid = $DB->insert_record('block_hubcourse_versions', $version);

            $hubcoursecontext = block_hubcourseinfo_getcontextfromhubcourse($hubcourse);

            $courseuploadform->save_stored_file('coursefile', $hubcoursecontext->id,
                'block_hubcourse', 'course', $versionid, '/');

            $hubcourse->stableversion = $versionid;
            $DB->update_record('block_hubcourses', $hubcourse);
        }
    }

    fulldelete($extractedpath);
    fulldelete($archivepath);

    redirect(new moodle_url('/course/view.php', ['id' => $courseid]));
} catch (Exception $ex) {
    delete_course($courseid);
    fulldelete($extractedpath);
    fulldelete($archivepath);
    throw new Exception(get_string('error_cannotrestore', 'block_hubcourseupload'));
} catch (Error $ex) {
    delete_course($courseid);
    fulldelete($extractedpath);
    fulldelete($archivepath);
    throw new Exception(get_string('error_cannotrestore', 'block_hubcourseupload'));
}