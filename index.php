<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Main file
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('id', PARAM_INT);

require_login($courseid);
$context = context_course::instance($courseid);
require_capability('tool/albertolarah:view', $context);

$course = $DB->get_record_sql("SELECT summary FROM {course} WHERE id = ?", [$courseid]);

$url = new moodle_url('/admin/tool/albertolarah/index.php', ['id' => $courseid]);

$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title(get_string('pluginname', 'tool_albertolarah'));
$PAGE->set_heading(get_string('pluginname', 'tool_albertolarah'));

// Only user with capability and a valid sesskey can delete a entry.
if ($deleteid = optional_param('delete', null, PARAM_INT)) {
    require_sesskey();
    require_capability('tool/albertolarah:edit', $context);
    \tool_albertolarah\manager::delete($deleteid);
    redirect($url, get_string('deletesuccess', 'tool_albertolarah'));
}


// Output.
$outputpage = new \tool_albertolarah\output\main_page($courseid);
$output = $PAGE->get_renderer('tool_albertolarah');
echo $output->header();
echo $output->render($outputpage);
echo $output->footer();