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
require_once("../../../config.php");
require_once("../../../course/lib.php");
require_once("lib.php");

require_login();
$systemcontext = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_url('/local/course_fields/course/index.php');
$PAGE->set_context($systemcontext);
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add('test');

/// Print headings
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('coursefieldsmanage', 'local_course_fields'));

echo $OUTPUT->skip_link_target();
echo $OUTPUT->box_start('categorybox');
print_whole_category_list_for_coursefields();
echo $OUTPUT->box_end();

echo $OUTPUT->footer();