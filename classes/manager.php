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
 * Class manager
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_albertolarah;

defined('MOODLE_INTERNAL') || die();

/**
 * Class manager for manage CRUD of the table.
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class  manager {

    const TABLE = 'tool_albertolarah';

    /**
     * Get an entry
     *
     * @param $id
     *
     * @return mixed
     * @throws \dml_exception
     */
    public static function get($id) {
        global $DB;
        return $DB->get_record(self::TABLE, ['id' => $id], '*', MUST_EXIST);
    }

    /**
     * Create an entry
     *
     * @param stdClass $data
     * @return int id of the new entry
     *
     * @throws \dml_exception
     */
    public static function create($data) : int {
        global $DB;
        $entryid = $DB->insert_record(self::TABLE, (object)[
            'courseid' => $data->courseid,
            'name' => $data->name,
            'completed' => $data->completed,
            'priority' => isset($data->priority) ? $data->priority : 1,
            'description' => isset($data->description_editor['text']) ? $data->description_editor['text'] : '',
            'description_format' => isset($data->description_editor['format']) ? $data->description_editor['format'] : 1,
            'timecreated' => time(),
            'timemodified' => time()
        ]);

        if (isset($data->description_editor)) {
            $context = \context_course::instance($data->courseid);
            $data = file_postupdate_standard_editor($data, 'description',
                form::build_editor_options($context), $context, 'tool_albertolarah', 'entry', $entryid);
            $updatedata = ['id' => $entryid, 'description' => $data->description,
                           'descriptionformat' => $data->descriptionformat];
            $DB->update_record('tool_albertolarah', $updatedata);
        }

        // Trigger event.
        $event = \tool_albertolarah\event\entry_created::create([
            'context' => \context_course::instance($data->courseid),
            'objectid' => $entryid
        ]);
        $event->trigger();

        return $entryid;
    }

    /**
     * Update an entry
     *
     * @param \stdClass $data
     *
     * @throws \dml_exception
     */
    public static function update(\stdClass $data) {
        global $DB;
        $data->timemodified = time();
        $data->description = isset($data->description_editor['text']) ? $data->description_editor['text'] : '';
        $data->description_format = isset($data->description_editor['format']) ? $data->description_editor['format'] : 1;

        $DB->update_record(self::TABLE, $data);

        if (isset($data->description_editor)) {
            $context = \context_course::instance($data->courseid);
            $data = file_postupdate_standard_editor($data, 'description',
                form::build_editor_options($context), $context, 'tool_albertolarah', 'entry', $data->id);
            $updatedata = ['id' => $data->id, 'description' => $data->description,
                           'descriptionformat' => $data->descriptionformat];
            $DB->update_record('tool_albertolarah', $updatedata);
        }

        // Trigger event.
        $entry = self::get($data->id);
        $event = \tool_albertolarah\event\entry_updated::create([
            'context' => \context_course::instance($entry->courseid),
            'objectid' => $entry->id
        ]);
        $event->add_record_snapshot('tool_albertolarah', $entry);
        $event->trigger();
    }

    /**
     Delete an entry
     *
     * @param int $id
     *
     * @return mixed
     * @throws \dml_exception
     */
    public static function delete(int $id) {
        global $DB;
        $entry = self::get($id);

        // Trigger event.
        $event = \tool_albertolarah\event\entry_deleted::create([
            'context' => \context_course::instance($entry->courseid),
            'objectid' => $entry->id
        ]);
        $event->add_record_snapshot('tool_albertolarah', $entry);
        $event->trigger();

        return $DB->delete_records(self::TABLE, ['id' => $id]);
    }
}