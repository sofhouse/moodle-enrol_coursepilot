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
 * Plugin strings are defined here.
 *
 * @package     enrol_coursepilot
 * @category    string
 * @copyright   2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// General.
$string['pluginname'] = 'Enrol Course Pilot';

// Settings.
$string['setting_configpage'] = 'Course Pilot Configuration';
$string['setting_enable'] = 'Enable Course Pilot';
$string['setting_enable_desc'] = 'Feature Flag Course Pilot enrolment method.';
$string['setting_template_categories'] = 'Template Categories';
$string['setting_template_categories_desc'] = 'These categories will be used when listing template courses for copying.';
$string['setting_formation_categories'] = 'Formations Categories';
$string['setting_formation_categories_desc'] = 'These categories will be used for validating when users are enrolled to formations courses.';

// API.
$string['api_plugin_disabled'] = 'The Course Pilot plugin is disabled.';
$string['api_invalid_courseid'] = 'The template course {$a} does not exist or the category is not set to be used as a template.';
$string['api_invalid_formationid'] = 'The formation course category {$a} does not exist or is not set to be used as a formation.';
$string['api_course_was_not_copied'] = 'The course could was not copied.';
$string['api_no_permission'] = 'You do not have permission to perform this action.';
$string['api_course_copy_queued'] = 'The course copy has been queued and will be created shortly';
