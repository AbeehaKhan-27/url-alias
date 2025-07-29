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
 * Searches for the aliases.
 *
 * @package   local_friendly_url
 * @copyright 2025, Abeeha Khan
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined ('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/formslib.php");

/**
 * Searches for the aliases.
 *
 * @package   local_friendly_url
 * @copyright 2025, Abeeha Khan
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class search extends moodleform {
    /**
     * Search alias definition
     * @return void
     * @throws coding_exception
     */
    public function definition(): void {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('text', 'query', get_string('query_keyword', 'local_friendly_url'));
        $mform->setType('query', PARAM_NOTAGS);
        $mform->addRule('query', get_string('err_required', 'local_friendly_url'), 'required', null, 'client');
        $this->add_action_buttons(true, get_string('filter_button', 'local_friendly_url'));
    }
}
