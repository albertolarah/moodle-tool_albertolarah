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

require_login();

$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record_sql("SELECT summary FROM {course} WHERE id = ?", [$courseid]);

$url = new moodle_url('/admin/tool/albertolarah/index.php', ['id' => $courseid]);

$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title(get_string('helloworld', 'tool_albertolarah'));
$PAGE->set_heading(get_string('pluginname', 'tool_albertolarah'));

echo $OUTPUT->header();
echo html_writer::div(get_string('helloworld', 'tool_albertolarah', $courseid));
echo html_writer::div(format_string($course->summary)); // Not support images or media yet.

// Display table.
$table = new tool_albertolarah_table_sql('tool_albertolarah', $courseid);
$table->out(10, true);
echo $OUTPUT->footer();