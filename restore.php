<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/classes/courseupload_form.php');
require_once(__DIR__ . '/classes/versionconfirm_form.php');
require_once(__DIR__ . '/classes/pluginconfirm_form.php');
require_once(__DIR__ . '/../../backup/util/includes/restore_includes.php');
if (block_hubcourseupload_infoblockenabled()) {
    require_once(__DIR__ . '/../../blocks/hubcourseinfo/lib.php');
}

$versionconfirmform = new versionconfirm_form();
$pluginconfirmform = new pluginconfirm_form();
if ($versionconfirmform->is_cancelled() || $pluginconfirmform->is_cancelled()) {
    $data = $versionconfirmform->is_submitted() ? $versionconfirmform->get_jsondata() : $pluginconfirmform->get_jsondata();
    if (isset($data->archivepath)) {
        fulldelete($data->archivepath);
    }
    if (isset($data->extractedpath)) {
        fulldelete($data->extractedpath);
    }

    redirect(new moodle_url('/'));
}

$systemcontext = context_system::instance();
$usercontext = context_user::instance($USER->id);

require_capability('block/hubcourseupload:upload', $usercontext);

$step = optional_param('step', BLOCK_HUBCOURSEUPLOAD_STEP_PREPARE, PARAM_INT);

$versionconfirmformdata = null;
if ($versionconfirmform->is_submitted()) {
    $versionconfirmformdata = $versionconfirmform->get_jsondata();
    $step = $versionconfirmformdata->step;
    $mbzfilename = $versionconfirmformdata->mbzfilename;
}

$pluginconfirmformdata = null;
if ($pluginconfirmform->is_submitted()) {
    $pluginconfirmformdata = $pluginconfirmform->get_jsondata();
    $step = $pluginconfirmformdata->step;
    $mbzfilename = $pluginconfirmformdata->mbzfilename;
}

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_PREPARE) {
    $courseuploadform = new courseupload_form();
    if (!$courseuploadform->is_submitted()) {
        throw new Exception(get_string('error_filenotuploaded', 'block_hubcourseupload'));
    }

    $courseuploaddata = $courseuploadform->get_data();
    $mbzfilename = $courseuploadform->get_new_filename('coursefile');

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

    if ($CFG->version < $info->moodle_version) {
        $PAGE->set_context($systemcontext);
        $PAGE->set_pagelayout('standard');
        $PAGE->set_title(get_string('pluginname', 'block_hubcourseupload'));
        $PAGE->set_heading(get_string('pluginname', 'block_hubcourseupload'));
        echo $OUTPUT->header();
        $versionconfirm = new versionconfirm_form($info->moodle_version, $CFG->version, [
            'archivepath' => $archivepath,
            'info' => $info,
            'step' => BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED,
            'mbzfilename' => $mbzfilename
        ]);
        $versionconfirm->display();
        echo $OUTPUT->footer();
        exit;
    }

    $step = BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED;
}

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_VERSIONCONFIRMED) {
    if ($versionconfirmform->is_submitted()) {
        $archivepath = $versionconfirmformdata->archivepath;
        $info = $versionconfirmformdata->info;
    }

    $extractedname = restore_controller::get_tempdir_name($systemcontext->id, $USER->id);
    $extractedpath = $CFG->tempdir . '/backup/' . $extractedname . '/';
    $fb = get_file_packer('application/vnd.moodle.backup');
    if (!$fb->extract_to_pathname($archivepath, $extractedpath, null)) {
        throw new Exception(get_string('error_cannotextractfile', 'block_hubcourseupload'));
    }

    $plugins = block_hubcourseupload_getplugins($extractedpath);
    if (!block_hubcourseupload_valid($plugins)) {
        $PAGE->set_context($systemcontext);
        $PAGE->set_pagelayout('standard');
        $PAGE->set_title(get_string('pluginname', 'block_hubcourseupload'));
        $PAGE->set_heading(get_string('pluginname', 'block_hubcourseupload'));
        echo $OUTPUT->header();
        $pluginconfirmform = new pluginconfirm_form($plugins, [
            'archivepath' => $archivepath,
            'info' => $info,
            'step' => BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED,
            'mbzfilename' => $mbzfilename,
            'extractedname' => $extractedname,
            'extractedpath' => $extractedpath,
            'plugins' => $plugins
        ]);
        $pluginconfirmform->display();
        echo $OUTPUT->footer();
        exit;
    }

    $step = BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED;
}

if ($step == BLOCK_HUBCOURSEUPLOAD_STEP_PLUGINCONFIRMED) {
    if ($pluginconfirmform->is_submitted()) {
        $archivepath = $pluginconfirmformdata->archivepath;
        $info = $pluginconfirmformdata->info;
        $extractedname = $pluginconfirmformdata->extractedname;
        $extractedpath = $pluginconfirmformdata->extractedpath;
        $plugins = $pluginconfirmformdata->plugins;
    }

    raise_memory_limit(MEMORY_EXTRA);

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

                if (!$fs) {
                    $fs = get_file_storage();
                }
                $fs->create_file_from_pathname([
                    'contextid' => $hubcoursecontext->id,
                    'component' => 'block_hubcourse',
                    'filearea' => 'course',
                    'itemid' => $versionid,
                    'filepath' => '/',
                    'filename' => $mbzfilename
                ], $archivepath);

                $hubcourse->stableversion = $versionid;
                $DB->update_record('block_hubcourses', $hubcourse);

                $standardmods = core_plugin_manager::standard_plugins_list('mod');
                $standardblocks = core_plugin_manager::standard_plugins_list('block');

                if (!is_array($plugins)) {
                    $plugins = (array)$plugins;
                }

                foreach ($plugins['mod'] as $modname => $version) {
                    if (in_array($modname, $standardmods)) {
                        continue;
                    }

                    $dependency = new stdClass();
                    $dependency->id = 0;
                    $dependency->versionid = $versionid;
                    $dependency->requiredpluginname = 'mod_' . $modname;
                    $dependency->requiredpluginversion = $version;

                    $DB->insert_record('block_hubcourse_dependencies', $dependency);
                }

                foreach ($plugins['block'] as $blockname => $version) {
                    if (in_array($modname, $standardblocks)) {
                        continue;
                    }

                    $dependency = new stdClass();
                    $dependency->id = 0;
                    $dependency->versionid = $versionid;
                    $dependency->requiredpluginname = 'block_' . $blockname;
                    $dependency->requiredpluginversion = $version;

                    $DB->insert_record('block_hubcourse_dependencies', $dependency);
                }
            }
        }

        fulldelete($extractedpath);
        fulldelete($archivepath);

        redirect(new moodle_url('/course/view.php', ['id' => $courseid]));
    } catch (Exception $ex) {
        delete_course($courseid);
        fulldelete($extractedpath);
        fulldelete($archivepath);
        throw new Exception(get_string('error_cannotrestore', 'block_hubcourseupload') . $ex->getMessage());
    } catch (Error $ex) {
        delete_course($courseid);
        fulldelete($extractedpath);
        fulldelete($archivepath);
        throw new Exception(get_string('error_cannotrestore', 'block_hubcourseupload') . $ex->getMessage());
    }
}