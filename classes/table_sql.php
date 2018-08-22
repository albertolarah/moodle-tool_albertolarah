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

namespace tool_albertolarah;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/tablelib.php');

/**
 * Class tool_albertolarah_table_sql for displaying tool_albertolarah table
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class table_sql extends \table_sql {

    /** @var \context_course */
    protected $context;

    /**
     * Sets up the table_log parameters.
     *
     * @param string $uniqueid unique id of form.
     * @param int $courseid
     */

    /**
     * table_sql constructor.
     *
     * @param $uniqueid
     * @param $courseid
     *
     * @throws \coding_exception
     */
    public function __construct($uniqueid, int $courseid) {
        global $PAGE;

        parent::__construct($uniqueid);

        $columns = array('name', 'completed', 'priority', 'timecreated', 'timemodified');
        $headers = array(
            get_string('name', 'tool_albertolarah'),
            get_string('completed', 'tool_albertolarah'),
            get_string('priority', 'tool_albertolarah'),
            get_string('timecreated', 'tool_albertolarah'),
            get_string('timemodified', 'tool_albertolarah'),
        );
        $this->context = \context_course::instance($courseid);
        if (has_capability('tool/albertolarah:edit', $this->context)) {
            $columns[] = 'edit';
            $headers[] = '';
        }

        $this->define_columns($columns);
        $this->define_headers($headers);

        $this->pageable(true);
        $this->collapsible(true);
        $this->sortable(true, 'name');
        $this->no_sorting('edit');
        $this->is_downloadable(true);

        $this->define_baseurl($PAGE->url);
        $this->add_separator();
        $this->context = \context_course::instance($courseid);
        $this->set_sql('id, name, completed, priority, timecreated, timemodified',
            '{tool_albertolarah}', 'courseid = ?', [$courseid]);
    }

    /**
     * Displays column completed
     *
     * @param \stdClass $row
     * @return string
     * @throws \coding_exception
     */
    protected function col_completed($row) {
        return $row->completed ? get_string('yes') : get_string('no');
    }

    /**
     * Displays column priority
     *
     * @param \stdClass $row
     * @return string
     * @throws \coding_exception
     */
    protected function col_priority($row) {
        return $row->priority ? get_string('yes') : get_string('no');
    }

    /**
     * Displays column name
     *
     * @param \stdClass $row
     * @return string
     */
    protected function col_name($row) {
        return format_string($row->name, true,
            ['context' => $this->context]);
    }

    /**
     * Displays column timecreated
     *
     * @param \stdClass $row
     * @return string
     * @throws \coding_exception
     */
    protected function col_timecreated($row) {
        return userdate($row->timecreated, get_string('strftimedatetimeshort'));
    }

    /**
     * Displays column timemodified
     *
     * @param \stdClass $row
     * @return string
     * @throws \coding_exception
     */

    protected function col_timemodified($row) {
        return userdate($row->timemodified, get_string('strftimedatetimeshort'));
    }

    /**
     * Display edit column as icon.
     *
     * @param $row
     *
     * @return string
     * @throws \moodle_exception
     */
    protected function col_edit($row) {
        global $OUTPUT;
        if (isset($row)) {
            $url = new \moodle_url('/admin/tool/albertolarah/edit.php', ['id' => $row->id]);
            $actionicon = $OUTPUT->pix_icon('t/editinline', '');
            return \html_writer::link($url, $actionicon);
        }

        return '';
    }

    /**
     *  Overwrite build method to add new rows. For example for total sum or separators...
     *  This function is only for testing.
     */
    public function build_table() {
        parent::build_table();
        $this->add_separator();
        $this->add_data(['a', 'a', '-', '-', '-', '']);
    }
}