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
require_once($CFG->libdir.'/adminlib.php');

$id = required_param('id', PARAM_INT); // Category id

$PAGE->set_category_by_id($id);
$cateory = $PAGE->category;
$systemcontext = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_url('/local/course_fields/course/category.php?id='.$id);
$PAGE->set_context($systemcontext);
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add($cateory->name." ".get_string('catagorycourses', 'local_course_fields'));

/// Print the header
echo $OUTPUT->header();
echo $OUTPUT->heading($cateory->name." - ".get_string('catagorycourses', 'local_course_fields'));

$courses = $DB->get_records('course', array('category'=>$id), 'sortorder ASC');

$table = new html_table();
$table->head  = array(get_string('coursename', 'local_course_fields'), get_string('edit'));
$table->align = array('left', 'right');
$table->width = '95%';
$table->attributes['class'] = 'generaltable profilefield';

foreach($courses as $course){
	$table->data[] = array(format_string($course->fullname), course_icons($course));
}

if (count($table->data)) {
    echo html_writer::table($table);
} else {
    echo $OUTPUT->notification($strnofields);
}

echo $OUTPUT->footer();
die;

function course_icons($course) {
    global $CFG, $USER, $DB, $OUTPUT;

    $stredit     = get_string('edit');

    /// Edit
    $editstr = '<a title="'.$stredit.'" href="course.php?id='.$course->id.'"><img src="'.$OUTPUT->pix_url('t/edit') . '" alt="'.$stredit.'" class="iconsmall" /></a> ';

    return $editstr;
}
