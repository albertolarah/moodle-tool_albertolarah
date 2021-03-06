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
 * Class tool_albertolarah_form
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_albertolarah;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/formslib.php');

/**
 * Class tool_albertolarah_form for displaying an editing form
 *
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class form extends \moodleform {

    /**
     * Form definition
     *
     * @throws \HTML_QuickForm_Error
     * @throws \coding_exception
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name',
            get_string('name', 'tool_albertolarah'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->addRule('name', '', 'required', null);
        $mform->addElement('advcheckbox', 'completed',
            get_string('alreadycompleted', 'tool_albertolarah'));

        $editoroptions = !(empty($this->_customdata['editoroptions'])) ? $this->_customdata['editoroptions'] : null;

        $mform->addElement('editor', 'description_editor',
            get_string('editordescription', 'tool_albertolarah'),
            null, $editoroptions);

        $this->add_action_buttons();
    }

    /**
     * Build the editor options using the given context.
     *
     * @param \context $context A Moodle context
     * @return array
     */
    public static function build_editor_options(\context $context) {
        global $CFG;
        return [
            'context' => $context,
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'maxbytes' => $CFG->maxbytes,
            'noclean' => true,
            'autosave' => true
        ];
    }
    /**
     * Form validation
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) : array {
        global $DB;
        $errors = parent::validation($data, $files);

        // Check that name is unique for the course.
        if ($DB->record_exists_select('tool_albertolarah',
            'name = :name AND id <> :id AND courseid = :courseid',
            ['name' => $data['name'], 'id' => $data['id'], 'courseid' => $data['courseid']])) {
            $errors['name'] = get_string('errornameexists', 'tool_albertolarah');
        }

        return $errors;
    }
}