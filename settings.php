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

global $PAGE;

if ($hassiteconfig) { // needs this condition or there is error on login page
    $ADMIN->add('courses', new admin_externalpage('local_course_fields',
            get_string('coursefields', 'local_course_fields'),
            new moodle_url('/local/course_fields/profile/index.php')));
}