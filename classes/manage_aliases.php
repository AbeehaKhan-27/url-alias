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
 * Manages the aliases.
 *
 * @package   friendly_url
 * @copyright 2025, Abeeha Khan
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class manage_aliases {
    /** Inserts the URLs into the database.
     * @param string $friendlyurl
     * @param string $destinationurl
     * @return bool true if successful
     */
    public function create_alias(string $friendlyurl, string $destinationurl): bool {
        global $DB;
        $insert = new stdClass();
        $insert->friendly = $friendlyurl;
        $insert->destination = $destinationurl;
        try {
            return $DB->insert_record('alias', $insert, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /** Gets the URLs.
     * @param int $currentpage
     * @param string $query
     * @return array of URLs
     * @throws dml_exception
     */
    public function get_alias(int $currpage, string $query): array {
        global $DB;
        $totalpages = 3;
        $page = $currpage ?? 0;
        $select = strlen($query) != 0 ?
            $DB->sql_like('friendly', ':friendly')
            : '';
        $params = ['friendly' => '%'.$DB->sql_like_escape($query).'%',
            ];
        $count = $DB->count_records_select('alias', $select, $params);
        try {
            $aliases = $DB->get_records_select(
                'alias',
                $select,
                $params,
                'id',
                '*',
                $totalpages * $page,
                $totalpages);
            return [
                'aliases' => array_values($aliases),
                'page' => $page,
                'pages' => ceil($count / $totalpages),
                'count' => $count,
            ];
        } catch (dml_exception $e) {
            return [];
        }
    }

    /** Gets a specific URL.
     * @param int $aliasid the record we're trying to get
     * @return object|false record data or false if not found.
     */
    public function get_alias_by_url(int $aliasid) {
        global $DB;
            return $DB->get_record('alias', ['id' => $aliasid]);
    }

    /** Updates details for a single URL.
     * @param int $oldurl the URL we're trying to update.
     * @param string $friendlyurl the new friendly url.
     * @param string $destinationurl the new destination url.
     * @return bool the url data or false if not found.
     */
    public function update_alias(int $oldurl, string $friendlyurl, string $destinationurl): bool {
        global $DB;
        $update = new stdClass();
        $update->id = $oldurl;
        $update->friendly = $friendlyurl;
        $update->destination = $destinationurl;
        try {
            return $DB->update_record('alias', $update);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /** Deletes a URL.
     * @param  int $aliasid the alias we're trying to delete.
     * @return bool true if success
     */
    public function delete_alias(int $aliasid) {
        global $DB;
        return $DB->delete_records('alias', ['id' => $aliasid]);
    }
}
