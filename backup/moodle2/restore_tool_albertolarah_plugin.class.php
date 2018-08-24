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
 * Class for restore proccess for tool_albertolarah
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../../backup/moodle2/restore_tool_plugin.class.php');

/**
 * Class restore_tool_albertolarah_plugin
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_tool_albertolarah_plugin extends restore_tool_plugin {
    protected $entries;

    /**
     * @return array
     */
    protected function define_course_plugin_structure() {
        $paths = array();
        $paths[] = new restore_path_element('albertolarah', '/course/albertolarah');

        return $paths;
    }

    /**
     * @param array|stdClass|object $data
     * @throws dml_exception
     */
    public function process_mitxel($data) {
        global $DB;

        $data = (object) $data;

        // Store the old id.
        $oldid = $data->id;

        // Change the values before we insert it.
        $data->courseid = $this->task->get_courseid();
        $data->timecreated = time();
        $data->timemodified = $data->timecreated;

        // Now we can insert the new record.
        $data->id = $DB->insert_record('tool_albertolarah', $data);

        // Add the array of tools we need to process later.
        $this->entries[$data->id] = $data;

        // Set up the mapping.
        $this->set_mapping('albertolarah', $oldid, $data->id);
    }
}
