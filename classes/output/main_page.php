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
 * Class tool_albertolarah\output\entries_list
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_albertolarah\output;
defined('MOODLE_INTERNAL') || die();
use renderer_base;
use moodle_url;
use tool_albertolarah\table_sql;
use context_course;
/**
 * Class tool_albertolarah\output\entries_list
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class main_page implements \templatable, \renderable {
    /** @var int */
    protected $courseid;
    /**
     * entries_list constructor.
     * @param int $courseid
     */
    public function __construct($courseid) {
        $this->courseid = $courseid;
    }
    /**
     * Implementation of exporter from templatable interface
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output) {
        $output;
        $course = get_course($this->courseid);
        $context = context_course::instance($this->courseid);

        // Display table.
        ob_start();
        $table = new table_sql('tool_albertolarah', $this->courseid);
        $table->out(20, false);
        $tablecontent = ob_get_clean();

        $url = new moodle_url('/admin/tool/albertolarah/edit.php', ['courseid' => $this->courseid]);

        return [
            'courseid' => $this->courseid,
            'coursename' => format_string($course->fullname, true, ['context' => $context]),
            'coursedescription' => format_string($course->summary, true, ['context' => $context]),
            'tablerender' => $tablecontent,
            'canaddentry' => has_capability('tool/albertolarah:edit', $context),
            'addlink' => $url->out(false)
        ];
    }
}