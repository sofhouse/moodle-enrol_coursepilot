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
 * Plugin administration pages are defined here.
 *
 * @package     enrol_coursepilot
 * @category    admin
 * @copyright   2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $pluginname = 'enrol_coursepilot';
    $categories = core_course_category::make_categories_list('moodle/category:manage');

    $ADMIN->add('enrolments', new admin_category('enrolcoursepilotfolder', new lang_string('pluginname', $pluginname),
        $this->is_enabled() === false));

    $visiblename = get_string('setting_configpage', $pluginname);
    $settings = new admin_settingpage($section, $visiblename, 'moodle/site:config', $this->is_enabled() === false);

    // Plugin Feature flag.
    $settings->add(new admin_setting_configcheckbox(
        "{$pluginname}/enable",
        new lang_string('setting_enable', $pluginname),
        new lang_string('setting_enable_desc', $pluginname),
        0
    ));

    // Template categories.
    $settings->add(new admin_setting_configmultiselect(
        "$pluginname/templatecategories",
        new lang_string('setting_template_categories', $pluginname),
        new lang_string('setting_template_categories_desc', $pluginname),
        [],
        $categories
    ));

    // Formations categories.
    $settings->add(new admin_setting_configmultiselect(
        "$pluginname/formationcategories",
        new lang_string('setting_formation_categories', $pluginname),
        new lang_string('setting_formation_categories_desc', $pluginname),
        [],
        $categories
    ));
}
