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
 * Manager tests.
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
/**
 * Manager tests.
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_albertolarah_manager_testcase extends advanced_testcase {
    /**
     * Set up for the tests.
     */
    public function setUp() {
        $this->resetAfterTest();
    }

    /**
     * Test insert method
     */
    public function test_insert() {
        $course = $this->getDataGenerator()->create_course();
        $newnetryid = \tool_albertolarah\manager::create((object)[
            'courseid' => $course->id,
            'name' => 'PHPUNIT entry',
            'completed' => 1,
            'priority' => 1
        ]);

        $entry = \tool_albertolarah\manager::get($newnetryid);
        $this->assertEquals($course->id, $entry->courseid);
        $this->assertEquals('PHPUNIT entry', $entry->name);
        $this->assertEquals(1, $entry->completed);
        $this->assertEquals(1, $entry->priority);
    }

    /**
     * Test delete method
     */
    public function test_delete() {
        $course = $this->getDataGenerator()->create_course();
        $newnetryid = \tool_albertolarah\manager::create((object)[
            'courseid' => $course->id,
            'name' => 'PHPUNIT entry',
            'completed' => 1,
            'priority' => 1
        ]);
        $this->expectException('dml_missing_record_exception');
        \tool_albertolarah\manager::delete($newnetryid);
        \tool_albertolarah\manager::get($newnetryid);
    }

    /**
     * Test update method
     */
    public function test_update() {
        $course = $this->getDataGenerator()->create_course();
        $newnetryid = \tool_albertolarah\manager::create((object)[
            'courseid' => $course->id,
            'name' => 'PHPUNIT entry',
            'completed' => 1,
            'priority' => 1
        ]);

        $updatedata = new stdClass();
        $updatedata->id = $newnetryid;
        $updatedata->name = 'PHPUNIT entry updated';
        \tool_albertolarah\manager::update($updatedata);
        $entry = \tool_albertolarah\manager::get($newnetryid);
        $this->assertEquals('PHPUNIT entry updated', $entry->name);
    }
}