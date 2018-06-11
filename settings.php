<?php
defined('MOODLE_INTERNAL') || die;

require_once __DIR__ . '/lib.php';

if ($ADMIN->fulltree) {

    $firstid = 0;
    $categories = $DB->get_records('course_categories', ['visible' => 1]);
    if (count($categories) > 0) {
        $firstid = $categories[0]->id;
    }
    $categoriesoptions = [];
    foreach ($categories as $category) {
        $categoriesoptions[$category->id] = $category->name;
    }

    $settings->add(
        new admin_setting_configcheckbox(
            'block_hubcourseupload/allowcapabilitychange',
            get_string('settings:allowcapabilitychange', 'block_hubcourseupload'),
            get_string('settings:allowcapabilitychange_description', 'block_hubcourseupload'),
            true, true, false
        )
    );

    $settings->add(
        new admin_setting_configcheckbox(
            'block_hubcourseupload/autoenableguestenrol',
            get_string('settings:autoenableguestenrol', 'block_hubcourseupload'),
            get_string('settings:autoenableguestenrol_description', 'block_hubcourseupload'),
            true, true, false
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_hubcourseupload/maxfilesize',
            get_string('settings:maxfilesize', 'block_hubcourseupload'),
            get_string('settings:maxfilesize_description', 'block_hubcourseupload'),
            '800', PARAM_INT)
    );

    $settings->add(
        new admin_setting_configselect(
            'block_hubcourseupload/defaultcategory',
            get_string('settings:defaultcategory', 'block_hubcourseupload'),
            get_string('settings:defaultcategory_description', 'block_hubcourseupload'),
            $firstid, $categoriesoptions
        )
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