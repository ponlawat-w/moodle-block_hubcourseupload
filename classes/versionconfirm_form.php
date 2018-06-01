<?php
require_once __DIR__ . '/../../../lib/formslib.php';

class versionconfirm_form extends moodleform
{
    public $courseversion;
    public $siteversion;
    public $jsondata;

    public function __construct($courseversion = 0, $siteversion = 0, $data = null)
    {
        $this->courseversion = $courseversion;
        $this->siteversion = $siteversion;
        $this->jsondata = json_encode($data);
        parent::__construct();
    }

    public function get_jsondata()
    {
        return json_decode($this->get_data()->jsondata);
    }

    public function definition()
    {
        $form = &$this->_form;
        $form->addElement('html', get_string('warning_moodleversion', 'block_hubcourseupload', ['original' => $this->courseversion, 'current' => $this->siteversion]));
        $form->addElement('hidden', 'jsondata', $this->jsondata);
        $form->setDefault('jsondata', $this->jsondata);
        $this->add_action_buttons(true, get_string('proceedanyway', 'block_hubcourseupload'));
    }
}