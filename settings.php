<?php
defined('MOODLE_INTERNAL') || die;

require_once __DIR__ . '/lib.php';

if ($ADMIN->fulltree) {

    $settings->add(
        new admin_setting_configtext(
            'block_hubcourseupload/maxfilesize',
            get_string('settings:maxfilesize', 'block_hubcourseupload'),
            get_string('settings:maxfilesize_description', 'block_hubcourseupload'),
            '800', PARAM_INT)
    );

    if (block_hubcourseupload_infoblockenabled()) {
        $settings->add(
            new admin_setting_configcheckbox(
                'block_hubcourseinfo/autocreateinfoblock',
                get_string('settings:autocreateinfoblock', 'block_hubcourseupload'),
                get_string('settings:autocreateinfoblock_decription', 'block_hubcourseupload'),
                true, true, false
            )
        );
    }
}