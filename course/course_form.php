<?php
/**
 * *************************************************************************
 * *                  Course Fields	                                      **
 * *************************************************************************
 * @copyright   emeneo.com                                                **
 * @link        emeneo.com                                                **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************
*/
defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->dirroot."/local/course_fields/profile/lib.php");

class course_form extends moodleform {
    protected $course;
    protected $context;

    function definition() {
        global $USER, $CFG, $DB;

        $mform    = $this->_form;
		
		$course        = $this->_customdata['course']; // this contains the data of this form
        $category      = $this->_customdata['category'];
        $editoroptions = $this->_customdata['editoroptions'];
        $returnto = $this->_customdata['returnto'];

		$systemcontext   = get_context_instance(CONTEXT_SYSTEM);
        $categorycontext = get_context_instance(CONTEXT_COURSECAT, $category->id);

        if (!empty($course->id)) {
            $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
            $context = $coursecontext;
        } else {
            $coursecontext = null;
            $context = $categorycontext;
        }

        $courseconfig = get_config('moodlecourse');

        $this->course  = $course;
        $this->context = $context;

		profile_definition($mform,$category->id,$course->id);

		$this->add_action_buttons();
        $mform->addElement('hidden', 'id', $course->id);
        $mform->setType('id', PARAM_INT);
	}

}