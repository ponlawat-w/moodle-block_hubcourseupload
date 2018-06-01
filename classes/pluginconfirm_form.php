<?php
require_once __DIR__ . '/../../../lib/formslib.php';

class pluginconfirm_form extends moodleform
{
    public $plugins;
    public $jsondata;

    public function __construct($plugins = [], $data = null)
    {
        $this->plugins = $plugins;
        $this->jsondata = json_encode($data);
        parent::__construct();
    }

    public function get_jsondata()
    {
        return json_decode($this->get_data()->jsondata);
    }

    public function definition()
    {
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