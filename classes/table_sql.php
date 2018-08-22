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
 * Class tool_albertolarah_table_sql
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/tablelib.php');

/**
 * Class tool_albertolarah_table_sql for displaying tool_albertolarah table
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_albertolarah_table_sql extends table_sql {

    /** @var context_course */
    protected $context;

    /**
     * Sets up the table_log parameters.
     *
     * @param string $uniqueid unique id of form.
     * @param int $courseid
     */
    public function __construct($uniqueid, $courseid) {
        global $PAGE;

        parent::__construct($uniqueid);

        $this->define_columns(array('name', 'completed', 'priority', 'timecreated', 'timemodified'));
        $this->define_headers(array(
            get_string('name', 'tool_albertolarah'),
            get_string('completed', 'tool_albertolarah'),
            get_string('priority', 'tool_albertolarah'),
            get_string('timecreated', 'tool_albertolarah'),
            get_string('timemodified', 'tool_albertolarah'),
        ));
        $this->pageable(true);
        $this->collapsible(true);
        $this->sortable(true);
        $this->is_downloadable(true);

        $this->define_baseurl($PAGE->url);
        $this->add_separator();
        $this->context = context_course::instance($courseid);
        $this->set_sql('name, completed, priority, timecreated, timemodified',
            '{tool_albertolarah}', 'courseid = ?', [$courseid]);
    }

    /**
     * Displays column completed
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_completed($row) {
        return $row->completed ? get_string('yes') : get_string('no');
    }

    /**
     * Displays column priority
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_priority($row) {
        return $row->priority ? get_string('yes') : get_string('no');
    }

    /**
     * Displays column name
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_name($row) {
        return format_string($row->name, true,
            ['context' => $this->context]);
    }

    /**
     * Displays column timecreated
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timecreated($row) {
        return userdate($row->timecreated, get_string('strftimedatetimeshort'));
    }

    /**
     * Displays column timemodified
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timemodified($row) {
        return userdate($row->timemodified, get_string('strftimedatetimeshort'));
    }

    public function build_table() {
        parent::build_table();
        $this->add_separator();
        $this->add_data(['a', 'a', '-', '-', '-']);
    }
}