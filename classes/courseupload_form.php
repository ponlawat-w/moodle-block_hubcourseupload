<?php
require_once __DIR__ . '/../../../lib/formslib.php';

class courseupload_form extends moodleform
{
    public function definition()
    {
        $maxsize = block_hubcourseupload_getmaxfilesize();

        $form = &$this->_form;

        $form->addElement('filepicker', 'coursefile', '', null,
            array('maxbytes' => $maxsize, 'accepted_types' => '.mbz'));

        $form->addElement('html', get_string('maxfilesize', 'block_hubcourseupload', $maxsize / 1024 / 1024));

        $this->add_action_buttons(false, get_string('continueupload', 'block_hubcourseupload'));
    }
}