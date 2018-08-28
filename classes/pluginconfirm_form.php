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
 * Class to confirm the difference of plugin usage of current site and course in mbz file
 *
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once __DIR__ . '/../../../lib/formslib.php';

/**
 * Class pluginconfirm_form
 * @package block_hubcourseupload
 */
class pluginconfirm_form extends moodleform {
    /**
     * @var array $plugins
     *  Plugin information
     */
    public $plugins;

    /**
     * @var string $jsondata
     *  json data of backup process to be passed to next step
     */
    public $jsondata;

    /**
     * pluginconfirm_form constructor.
     * @param array $plugins
     * @param string $data
     */
    public function __construct($plugins = [], $data = null) {
        $this->plugins = $plugins;
        $this->jsondata = json_encode($data);
        parent::__construct();
    }

    /**
     * Get json data
     * @return stdClass
     */
    public function get_jsondata() {
        return json_decode($this->get_data()->jsondata);
    }

    /**
     * Form definition
     * @throws coding_exception
     */
    public function definition() {
        $table = block_hubcourseupload_plugininfotable($this->plugins);
        $htmltable = block_hubcourseupload_plugininfotable_html($table);

        $text = html_writer::tag('p', get_string('warning_pluginversion', 'block_hubcourseupload'));
        $text .= html_writer::table($htmltable);

        $form = &$this->_form;
        $form->addElement('html', $text);
        $form->addElement('hidden', 'jsondata', $this->jsondata);
        $form->setDefault('jsondata', $this->jsondata);
        $this->add_action_buttons(true, get_string('proceedanyway', 'block_hubcourseupload'));
    }
}