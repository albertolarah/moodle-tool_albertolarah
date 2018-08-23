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
 * Editing or creating entries
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$id = optional_param('id', 0, PARAM_INT);
if ($id) {
    // We are going to edit an entry.
    $entry = \tool_albertolarah\manager::get($id);
    $courseid = $entry->courseid;
    $urlparams = ['id' => $id];
    $title = get_string('editentry', 'tool_albertolarah');
} else {
    // We are going to add an entry. Parameter courseid is required.
    $courseid = required_param('courseid', PARAM_INT);
    $entry = (object)['courseid' => $courseid];
    $urlparams = ['courseid' => $courseid];
    $title = get_string('newentry', 'tool_albertolarah');
}

$url = new moodle_url('/admin/tool/albertolarah/edit.php', $urlparams);
$context = context_course::instance($courseid);

$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading(get_string('pluginname', 'tool_albertolarah'));

require_login($courseid);

require_capability('tool/albertolarah:edit', $context);

$editoroptions = \tool_albertolarah\form::build_editor_options($context);

$form = new \tool_albertolarah\form(null, array('editoroptions' => $editoroptions));
if (!empty($entry->id)) {
    file_prepare_standard_editor($entry, 'description',
        $editoroptions,
        $context, 'tool_albertolarah', 'entry', $entry->id);
}
$form->set_data($entry);

$returnurl = new moodle_url('/admin/tool/albertolarah/index.php', ['id' => $courseid]);
if ($form->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $form->get_data()) {
    if ($data->id) {
        \tool_albertolarah\manager::update($data);
    } else {
        \tool_albertolarah\manager::create($data);
    }
    redirect($returnurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($title);

$form->display();

echo $OUTPUT->footer();