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

    public static function get($id) {
        global $DB;
        return $DB->get_record(self::TABLE, ['id' => $id], '*', MUST_EXIST);
    }

    /**
     * Create an entry
     *
     * @param $data
     *
     * @throws \dml_exception
     */
    public static function create($data) {
        global $DB;
        $DB->insert_record(self::TABLE, [
            'courseid' => $data->courseid,
            'name' => $data->name,
            'completed' => $data->completed,
            'priority' => 0,
            'timecreated' => time(),
            'timemodified' => time()
        ]);
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
        $DB->update_record(self::TABLE, [
            'id' => $data->id,
            'name' => $data->name,
            'completed' => $data->completed,
            'timemodified' => time()
        ]);
    }
}