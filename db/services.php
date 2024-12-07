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
 * Web services and external functions for enrol_coursepilot plugin are defined here.
 *
 * @package     enrol_coursepilot
 * @copyright   2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// We define the web service functions to install as pre-build functions. A pre-build function is not editable by administrator.
$functions = [
    'enrol_coursepilot_get_template_categories' => [
        'classname' => 'enrol_coursepilot\external',
        'methodname' => 'get_template_categories',
        'classpath' => 'enrol/coursepilot/classes/external.php',
        'description' => 'Get configured template categories.',
        'type' => 'read',
        'ajax' => true,
    ],
    'enrol_coursepilot_get_formations_categories' => [
        'classname' => 'enrol_coursepilot\external',
        'methodname' => 'get_formations_categories',
        'classpath' => 'enrol/coursepilot/classes/external.php',
        'description' => 'Get configured formations categories.',
        'type' => 'read',
        'ajax' => true,
    ],
    'enrol_coursepilot_create_course' => [
        'classname' => 'enrol_coursepilot\external',
        'methodname' => 'create_course',
        'classpath' => 'enrol/coursepilot/classes/external.php',
        'description' => 'Create a course into a formations category by copying a course from the templates category',
        'type' => 'read',
        'ajax' => true,
    ],
    'enrol_coursepilot_edit_enrollment' => [
        'classname' => 'enrol_coursepilot\external',
        'methodname' => 'edit_enrollment',
        'classpath' => 'enrol/coursepilot/classes/external.php',
        'description' => 'Enroll and unenroll a user into a formations course.',
        'type' => 'read',
        'ajax' => true,
    ],
];

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = [
    'Enrol Course Pilot Service' => [
        'functions' => [
            'enrol_coursepilot_get_template_categories',
            'enrol_coursepilot_get_formations_categories',
            'enrol_coursepilot_create_course',
            'enrol_coursepilot_edit_enrollment',
        ],
        'restrictedusers' => 0,
        'enabled' => 1,
    ],
];
