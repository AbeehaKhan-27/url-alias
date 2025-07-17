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
 * Library for the database. 
 *
 * @package   friendly_url 
 * @copyright 2025, Abeeha Khan 
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined ('MOODLE_INTERNAL') or die ();
require_once($CFG->dirroot.'/local/alias/classes/manage_aliases.php');

/**
 * Library for the database. 
 *
 * @package   friendly_url 
 * @copyright 2025, Abeeha Khan 
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class friendly_url_external extends external_api {
    /**
     * Parameters for delete_alias
     * @return external_function_parameters
     */
    public static function delete_alias_parameters() {
        return new external_function_parameters(
            ['aliasid' => new external_value(PARAM_INT, 'id of alias')]
        );
    }

    /**
     * Delete URL external function
     * @param int $aliasid
     * @return string
     * @throws invalid_parameter_exception
     */
    public static function delete_alias(int $aliasid): string {
        $params = self::validate_parameters(self::delete_alias_parameters(), ['aliasid'=> $aliasid]);
        $manager = new alias_manager();
        return $manager->delete_alias($aliasid);
    }

    /** 
     * Return for delete_alias
     * @return external_value
     */
    public static function delete_alias_returns() {
        return new external_value(PARAM_BOOL, 'URL successfuly deleted - True. ');
    }

}
?>