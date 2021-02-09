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
 * block_hubcourseupload class
 *
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once __DIR__ . '/lib.php';
require_once __DIR__ . '/classes/courseupload_form.php';

/**
 * Class block_hubcourseupload
 * @package block_hubcourseupload
 */
class block_hubcourseupload extends block_base {

    /**
     * Block initialization
     * @throws coding_exception
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_hubcourseupload');
        $this->version = 2018083000;
    }

    /**
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * @return bool
     */
    public function instance_can_be_hidden() {
        return false;
    }

    /**
     * @return array
     */
    public function applicable_formats() {
        return array(
            'all' => false,
            'my' => true,
            'site' => true
        );
    }

    /**
     * Fetch block content
     * @return stdClass
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function get_content() {
        global $USER;

        $this->page->requires->jquery();
        $this->page->requires->js(new moodle_url('/blocks/hubcourseupload/script.js'));
        $this->page->requires->strings_for_js(['coursefilechoose', 'draganddrop', 'pleasewait'], 'block_hubcourseupload');

        if (!$USER->id || !has_capability('block/hubcourseupload:upload', context_user::instance($USER->id))) {
            $this->content = new stdClass();
            $this->content->text = get_string($USER->id ? 'nocapability' : 'nosignin', 'block_hubcourseupload');
            $this->context->footer = '';
            return $this->content;
        }

        $uploader = new courseupload_form(new moodle_url('/blocks/hubcourseupload/restore.php'));

        $html = $uploader->render();

        $this->content = new stdClass();
        $this->content->text = $html;
        $this->content->footer = '';

        return $this->content;
    }

    /**
     * @return string
     */
    public function get_aria_role() {
        return 'application';
    }
}