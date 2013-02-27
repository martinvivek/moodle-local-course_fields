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

class local_course_fields{
	public function add_course_navigation($instancesnode, stdClass $instance) {
        //if ($instance->enrol !== 'waitlist') {
        //     throw new coding_exception('Invalid enrol instance type!');
        //}

        $context = get_context_instance(CONTEXT_COURSE, $instance->courseid);
        if (has_capability('enrol/waitlist:config', $context)) {
            $managelink = new moodle_url('/enrol/waitlist/edit.php', array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $instancesnode->add('test', $managelink, navigation_node::TYPE_SETTING);
        }
    }
}