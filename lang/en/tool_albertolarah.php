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
 * Strings for tool_albertolarah.
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Course entities management';
$string['wellcomemsg'] = 'You are management the entries for the course: {$a}';
// Table header.
$string['completed'] = 'Completed';
$string['priority'] = 'Priority';
$string['timecreated'] = 'Created';
$string['timemodified'] = 'Modified';
$string['name'] = 'Name';
// Capabilities definition.
$string['albertolarah:edit'] = 'Edit the entries data';
$string['albertolarah:view'] = 'View the entries data';
$string['addentry'] = 'Add new entry';
$string['editentry'] = 'Edit entry';
$string['errornameexists'] = 'Name must be unique in this course';
$string['alreadycompleted'] = 'Already completed?';
$string['name'] = 'Name';
$string['newentry'] = 'New entry';
$string['deletesuccess'] = 'The entry has been deleted';
// Form fields.
$string['editordescription'] = 'Extra info description';
$string['description'] = 'Description';

$string['confirmdeletemsg'] = 'Are you sure you want to delete this entry (this action is not reversible)?';

// Events.
$string['evententrycreated'] = 'Entry created';
$string['evententrydeleted'] = 'Entry deleted';
$string['evententryupdated'] = 'Entry updated';