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
 * Class for backup proccess for tool_albertolarah
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../../backup/moodle2/backup_tool_plugin.class.php');

/**
 * Class backup_tool_albertolarah_plugin
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_tool_albertolarah_plugin extends backup_tool_plugin {
    /**
     * @return backup_plugin_element
     * @throws base_element_struct_exception
     */
    protected function define_course_plugin_structure() {
        // Get the parent we will be adding these elements to.
        $plugin = $this->get_plugin_element();

        // Define each element separated
        $entries = new backup_nested_element('albertolarah', ['id'], [
            'courseid',
            'name',
            'completed',
            'priority',
            'description',
            'descriptionformat',
            'timecreated',
            'timemodified',
        ]);

        // Build elements hierarchy.
        $plugin->add_child($entries);

        // Set sources to populate the data.
        $entries->set_source_table('tool_albertolarah', [
            'courseid' => backup::VAR_COURSEID,
        ]);

        $entries->annotate_files('tool_albertolarah', 'entry', null);

        return $plugin;
    }
}
