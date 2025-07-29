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

/*
 * Manage page for local_friendly_url
 * @package   friendly_url
 * @copyright 2025, Abeeha Khan
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/friendly_url/classes/manage_aliases.php');
require_once($CFG->dirroot . '/local/friendly_url/classes/search.php');

require_login();
$systemcontext = context_system::instance();
require_capability('local/friendly_url:managealias', $systemcontext);

$PAGE->set_url(new moodle_url('/local/friendly_url/manage.php'));
$PAGE->set_context($systemcontext);
$PAGE->set_heading(get_string('manage_alias', 'local_friendly_url'));
$PAGE->set_title(get_string('manage_alias', 'local_friendly_url'));
$PAGE->requires->js_call_amd('local_friendly_url/confirm');

$manager = new manage_aliases();
$currpage = optional_param('page', 0, PARAM_INT);
$query = optional_param('q', '', PARAM_NOTAGS);
$perpage = 3;
$mform = new search();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot .'/local/friendly_url/manage.php', get_string('cancelled_filter_form', 'local_friendly_url'));
} else if ($fromform = $mform->get_data()) {
    if ($fromform->query) {
        redirect($CFG->wwwroot . "/local/friendly_url/manage.php?q=$fromform->query", get_string('submitted_filter_form', 'local_friendly_url'));
    }
}

if ($query !== '') {
    $mform->set_data(['query' => $query]);
}

$urls = $manager->get_alias($currpage, $query);
echo $OUTPUT->header();

$templatecontext = [
    'editurl' => new moodle_url('/local/friendly_url/edit.php'),
    "empty" => count(value: $urls['aliases']) == 0,
    "urls" => array_values(array: $urls['aliases']),
    "URL_not_found" => get_string('URL_not_found', 'local_friendly_url'),
    'create_button' => get_string('create_button', 'local_friendly_url'),
    'edit_button' => get_string('edit_button', 'local_friendly_url'),
    'delete_button' => get_string('delete_button', 'local_friendly_url'),
    "form" => $mform->render(),
];

echo $OUTPUT->render_from_template('local_friendly_url/manage', $templatecontext);
if (isset($urls['pages']) && $urls['count'] > $perpage) {
    $baseurl = new moodle_url('/local/friendly_url/manage.php', ['page' => $currpage, 'q' => $query]);
    echo $OUTPUT->paging_bar($urls['count'], $currpage, $perpage, $baseurl);
}
echo $OUTPUT->footer();
