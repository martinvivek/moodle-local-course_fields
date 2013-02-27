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
require_once('course_form.php');

$id = required_param('id', PARAM_INT); // Course id
$returnto = optional_param('returnto', 0, PARAM_ALPHANUM);

$PAGE->set_pagelayout('standard');
$PAGE->set_url('/local/course_fields/course/course.php?id='.$id);

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);
$category = $DB->get_record('course_categories', array('id'=>$course->category), '*', MUST_EXIST);
require_login($course);

$streditcoursesettings = get_string("editcoursesettings",'local_course_fields');

profile_load_data($course);



$editoroptions = '';
$editform = new course_form(NULL, array('course'=>$course, 'category'=>$category, 'editoroptions'=>$editoroptions, 'returnto'=>$returnto));
if ($editform->is_cancelled()) {
	$url = new moodle_url($CFG->wwwroot.'/local/course_fields/course/category.php', array('id'=>$category->id));
	redirect($url);
}else if ($data = $editform->get_data()){
	profile_save_data($data);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($course->fullname." - ".$streditcoursesettings);

$editform->display();

echo $OUTPUT->footer();