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
 * External API.
 *
 * @package     enrol_coursepilot
 * @copyright   2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_coursepilot;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/course/externallib.php");

use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;

/**
 * Represents an external class that extends the external_api class.
 */
class external extends external_api {

    /**
     * Retrieves the settings for course categories.
     *
     * @param string $name Optional. The name of the course category to filter by.
     * @return array The settings for the specified course categories.
     */
    protected static function get_settings_course_categories($name = '') {
        global $DB;

        if (empty($name)) {
            return [];
        }

        $config = get_config('enrol_coursepilot', $name);

        if (empty($config) || !is_string($config)) {
            return [];
        }

        $categories = [];
        foreach (explode(',', $config) as $category) {
            $category = trim($category);
            $category = $DB->get_record('course_categories', ['id' => $category], 'id, name');

            if (!empty($category)) {
                $categories[$category->id] = [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            }
        }

        return $categories;
    }

    /**
     * Returns a list of coruse categories ids.
     * @return array
     */
    public static function get_template_categories() {
        return self::get_settings_course_categories('templatecategories');
    }

    /**
     * Returns description of method parameters.
     * @return external_function_parameters
     */
    public static function get_template_categories_parameters() {
        return new external_function_parameters(
            []
        );
    }

    /**
     * Returns description of method result value.
     * @return external_multiple_structure
     */
    public static function get_template_categories_returns(): external_multiple_structure {
        return new external_multiple_structure(
            new external_single_structure(
                [
                    'id' => new external_value(PARAM_INT, 'The category id.'),
                    'name' => new external_value(PARAM_TEXT, 'The category name.'),
                ]
            )
        );
    }

}
