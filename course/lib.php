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
function print_whole_category_list_for_coursefields($category=NULL, $displaylist=NULL, $parentslist=NULL, $depth=-1, $showcourses = false) {
    global $CFG;

    // maxcategorydepth == 0 meant no limit
    if (!empty($CFG->maxcategorydepth) && $depth >= $CFG->maxcategorydepth) {
        return;
    }

    if (!$displaylist) {
        make_categories_list($displaylist, $parentslist);
    }
	
    if ($category) {
        if ($category->visible or has_capability('moodle/category:viewhiddencategories', get_context_instance(CONTEXT_SYSTEM))) {
            print_category_info_for_coursefields($category, $depth, $showcourses);
        } else {
            return;  // Don't bother printing children of invisible categories
        }

    } else {
        $category = new stdClass();
        $category->id = "0";
    }

    if ($categories = get_child_categories($category->id)) {   // Print all the children recursively
        $countcats = count($categories);
        $count = 0;
        $first = true;
        $last = false;
        foreach ($categories as $cat) {
            $count++;
            if ($count == $countcats) {
                $last = true;
            }
            $up = $first ? false : true;
            $down = $last ? false : true;
            $first = false;

            print_whole_category_list_for_coursefields($cat, $displaylist, $parentslist, $depth + 1, $showcourses);
        }
    }
}

function print_category_info_for_coursefields($category, $depth=0, $showcourses = false) {
    global $CFG, $DB, $OUTPUT;

    $strsummary = get_string('summary');

    $catlinkcss = null;
    if (!$category->visible) {
        $catlinkcss = array('class'=>'dimmed');
    }
    static $coursecount = null;
    if (null === $coursecount) {
        // only need to check this once
        $coursecount = $DB->count_records('course') <= FRONTPAGECOURSELIMIT;
    }

    if ($showcourses and $coursecount) {
        $catimage = '<img src="'.$OUTPUT->pix_url('i/course') . '" alt="" />';
    } else {
        $catimage = "&nbsp;";
    }

    $courses = get_courses($category->id, 'c.sortorder ASC', 'c.id,c.sortorder,c.visible,c.fullname,c.shortname,c.summary');
    $context = get_context_instance(CONTEXT_COURSECAT, $category->id);
    $fullname = format_string($category->name, true, array('context' => $context));

    if ($showcourses and $coursecount) {
        echo '<div class="categorylist clearfix">';
        $cat = '';
        $cat .= html_writer::tag('div', $catimage, array('class'=>'image'));
        $catlink = html_writer::link(new moodle_url('/course/category.php', array('id'=>$category->id)), $fullname, $catlinkcss);
        $cat .= html_writer::tag('div', $catlink, array('class'=>'name'));

        $html = '';
        if ($depth > 0) {
            for ($i=0; $i< $depth; $i++) {
                $html = html_writer::tag('div', $html . $cat, array('class'=>'indentation'));
                $cat = '';
            }
        } else {
            $html = $cat;
        }
        echo html_writer::tag('div', $html, array('class'=>'category'));
        echo html_writer::tag('div', '', array('class'=>'clearfloat'));

        // does the depth exceed maxcategorydepth
        // maxcategorydepth == 0 or unset meant no limit
        $limit = !(isset($CFG->maxcategorydepth) && ($depth >= $CFG->maxcategorydepth-1));
        if ($courses && ($limit || $CFG->maxcategorydepth == 0)) {
            foreach ($courses as $course) {
                $linkcss = null;
                if (!$course->visible) {
                    $linkcss = array('class'=>'dimmed');
                }

                $coursename = get_course_display_name_for_list($course);
                //$courselink = html_writer::link(new moodle_url('/course/view.php', array('id'=>$course->id)), format_string($coursename), $linkcss);

                // print enrol info
                $courseicon = '';
                if ($icons = enrol_get_course_info_icons($course)) {
                    foreach ($icons as $pix_icon) {
                        $courseicon = $OUTPUT->render($pix_icon).' ';
                    }
                }

                $coursecontent = html_writer::tag('div', $courseicon.$courselink, array('class'=>'name'));

                if ($course->summary) {
                    $link = new moodle_url('/course/info.php?id='.$course->id);
                    $actionlink = $OUTPUT->action_link($link, '<img alt="'.$strsummary.'" src="'.$OUTPUT->pix_url('i/info') . '" />',
                        new popup_action('click', $link, 'courseinfo', array('height' => 400, 'width' => 500)),
                        array('title'=>$strsummary));

                    $coursecontent .= html_writer::tag('div', $actionlink, array('class'=>'info'));
                }

                $html = '';
                for ($i=0; $i <= $depth; $i++) {
                    $html = html_writer::tag('div', $html . $coursecontent , array('class'=>'indentation'));
                    $coursecontent = '';
                }
                echo html_writer::tag('div', $html, array('class'=>'course clearfloat'));
            }
        }
        echo '</div>';
    } else {
        echo '<div class="categorylist">';
        $html = '';
        $cat = html_writer::link(new moodle_url('/local/course_fields/course/category.php', array('id'=>$category->id)), $fullname, $catlinkcss);
        if (count($courses) > 0) {
            $cat .= html_writer::tag('span', ' ('.count($courses).')', array('title'=>get_string('numberofcourses'), 'class'=>'numberofcourse'));
        }

        if ($depth > 0) {
            for ($i=0; $i< $depth; $i++) {
                $html = html_writer::tag('div', $html .$cat, array('class'=>'indentation'));
                $cat = '';
            }
        } else {
            $html = $cat;
        }

        echo html_writer::tag('div', $html, array('class'=>'category'));
        echo html_writer::tag('div', '', array('class'=>'clearfloat'));
        echo '</div>';
    }
}