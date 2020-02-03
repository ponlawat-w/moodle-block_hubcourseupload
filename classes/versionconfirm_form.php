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
 * Form to confirm of moodle version difference from moodle version indicated in mbz file
 *
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once __DIR__ . '/../../../lib/formslib.php';

/**
 * Class versionconfirm_form
 * @package block_hubcourseupload
 */
class versionconfirm_form extends moodleform {

    /**
     * @var int $courseversion
     *  Moodle version from mbz file
     */
    public $courseversion;

    /**
     * @var int $siteversion
     *  Current moodle version
     */
    public $siteversion;

    /**
     * @var string $jsondata
     *  Json data of restoration to be passed to next step
     */
    public $jsondata;

    /**
     * versionconfirm_form constructor.
     * @param int $courseversion
     * @param int $siteversion
     * @param null $data
     */
    public function __construct($courseversion = 0, $siteversion = 0, $data = null) {
        $this->courseversion = $courseversion;
        $this->siteversion = $siteversion;
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
        $form = &$this->_form;
        $form->addElement('html', get_string('warning_moodleversion', 'block_hubcourseupload', ['original' => $this->courseversion, 'current' => $this->siteversion]));
        $form->addElement('hidden', 'jsondata', $this->jsondata);
        $form->setDefault('jsondata', $this->jsondata);
        $form->setType('jsondata', PARAM_RAW);
        $this->add_action_buttons(true, get_string('proceedanyway', 'block_hubcourseupload'));
    }
}