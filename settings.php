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
 * Admin settings pages
 *
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once __DIR__ . '/lib.php';

if ($ADMIN->fulltree) {

    $firstid = 0;
    $categories = $DB->get_records('course_categories', ['visible' => 1]);
    $categoriesoptions = [];
    foreach ($categories as $category) {
        if (!$firstid) {
            $firstid = $category->id;
        }
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