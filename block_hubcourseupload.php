<?php
require_once __DIR__ . '/lib.php';
require_once __DIR__ . '/classes/courseupload_form.php';

class block_hubcourseupload extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_hubcourseupload');
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