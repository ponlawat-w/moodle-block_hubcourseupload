<?php
require_once __DIR__ . '/lib.php';
require_once __DIR__ . '/classes/courseupload_form.php';

class block_hubcourseupload extends block_base
{
    public function init()
    {
        $this->title = get_string('uploadcoursetohub', 'block_hubcourseupload');
        $this->version = 2018053100;
    }

    public function has_config()
    {
        return true;
    }

    public function instance_can_be_hidden()
    {
        return false;
    }

    public function applicable_formats()
    {
        return array(
            'all' => false,
            'my' => true
        );
    }

    public function get_content()
    {
        global $USER;

        $this->page->requires->jquery();
        $this->page->requires->js(new moodle_url('/blocks/hubcourseupload/script.js'));
        $this->page->requires->strings_for_js(['coursefilechoose', 'draganddrop'], 'block_hubcourseupload');

        if (!has_capability('block/hubcourseupload:upload', context_user::instance($USER->id))) {
            $this->content = new stdClass();
            $this->content->text = get_string('nocapability', 'block_hubcourseupload');
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

    public function get_aria_role()
    {
        return 'application';
    }
}