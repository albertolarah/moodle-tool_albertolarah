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
 * tool_albertolarah upgrade script.
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade tool_albertolarah.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool
 */
function xmldb_tool_albertolarah_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2018082101) {

        // Define table tool_albertolarah to be created.
        $table = new xmldb_table('tool_albertolarah');

        // Adding fields to table tool_albertolarah.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('completed', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('priority', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table tool_albertolarah.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for tool_albertolarah.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2018082101, 'tool', 'albertolarah');
    }

    if ($oldversion < 2018082102) {
        // Define courseid as foreign key.
        $table = new xmldb_table('tool_albertolarah');
        $key = new xmldb_key('courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);
        // Launch add key courseid.
        $dbman->add_key($table, $key);
        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2018082102, 'tool', 'albertolarah');
    }

    if ($oldversion < 2018082103) {
        // Define a unique index (courseid + name).
        $table = new xmldb_table('tool_albertolarah');
        $index = new xmldb_index('courseidname', XMLDB_INDEX_UNIQUE, ['courseid', 'name']);
        // Conditionally launch add index courseidname.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }
        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2018082103, 'tool', 'albertolarah');
    }

    if ($oldversion < 2018082321) {
        // Define field description to be added to tool_albertolarah.
        $table = new xmldb_table('tool_albertolarah');
        $field = new xmldb_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'priority');
        // Conditionally launch add field description.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Define field descriptionformat to be added to tool_albertolarah.
        $field = new xmldb_field('descriptionformat', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'description');
        // Conditionally launch add field descriptionformat.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2018082321, 'tool', 'albertolarah');
    }
    return true;
}