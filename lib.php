<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * The enrol plugin coursepilot is defined here.
 *
 * @package     enrol_coursepilot
 * @copyright   2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class enrol_coursepilot_plugin.
 * The base class 'enrol_plugin' can be found at lib/enrollib.php.
 */
class enrol_coursepilot_plugin extends enrol_plugin {

    /**
     * Determines whether the standard editing UI should be used.
     *
     * @return bool True if the standard editing UI should be used, false otherwise.
     */
    public function use_standard_editing_ui() {
        return true;
    }

    /**
     * Edits the instance form for the enrolment plugin.
     *
     * This function is responsible for modifying the enrolment instance form
     * with additional fields or settings specific to this enrolment plugin.
     *
     * @param stdClass $instance The enrolment instance data.
     * @param MoodleQuickForm $mform The form being used to edit the instance.
     * @param context $context The context of the course or category.
     */
    public function edit_instance_form($instance, MoodleQuickForm $mform, $context) {
        // Do nothing by default.
    }

    /**
     * Validates the data for editing an enrolment instance.
     *
     * @param array $data The data submitted from the form.
     * @param array $files The files submitted from the form.
     * @param stdClass $instance The enrolment instance being edited.
     * @param context $context The context in which the enrolment instance exists.
     * @return array An array of validation errors, if any.
     */
    public function edit_instance_validation($data, $files, $instance, $context) {
        // No errors by default.
        debugging('enrol_plugin::edit_instance_validation() is missing. This plugin has no validation!', DEBUG_DEVELOPER);
        return [];
    }

    /**
     * Determines if an instance can be added to the specified course.
     *
     * @param int $courseid The ID of the course to check.
     * @return bool True if an instance can be added, false otherwise.
     */
    public function can_add_instance($courseid) {
        return true;
    }

}

/**
 * Retrieves a list of valid roles for course enrollment.
 *
 * @param string|null $archetype Optional. The role archetype to filter roles by. Default is null.
 * @return array An array of valid roles.
 */
function enrol_coursepilot_get_valid_roles($archetype = '') {
    // Possible archetypes to fetch roles from.
    $archetypes = ['student', 'teacher', 'editingteacher'];
    $validroles = [];

    // If a specific archetype is provided, use it.
    if (!empty($archetype) && is_string($archetype)) {
        $archetypes = [$archetype];
    }

    // Fetch roles based on the provided archetypes.
    foreach ($archetypes as $archetype) {
        $roles = get_archetype_roles($archetype);
        foreach ($roles as $role) {
            $validroles[] = $role->id;
        }
    }

    // Return the valid roles.
    return $validroles;
}
