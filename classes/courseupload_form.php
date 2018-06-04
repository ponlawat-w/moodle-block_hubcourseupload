<?php
require_once __DIR__ . '/../../../lib/formslib.php';

class courseupload_form extends moodleform
{
    public function definition()
    {
        global $DB;

        $maxsize = block_hubcourseupload_getmaxfilesize();
        $categories = $DB->get_records('course_categories', ['visible' => 1]);
        $categoriesoptions = [];
        foreach ($categories as $category) {
            $categoriesoptions[$category->id] = $category->name;
        }

        $form = &$this->_form;

        $form->addElement('filepicker', 'coursefile', '', null,
            array('maxbytes' => $maxsize, 'accepted_types' => '.mbz'));

        $form->addElement('html', get_string('maxfilesize', 'block_hubcourseupload', $maxsize / 1024 / 1024));
        $form->addElement('select', 'category', get_string('coursecategory'), $categoriesoptions);

        $this->add_action_buttons(false, get_string('continue'));
    }
}