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
 * External API Test cases for enrol_coursepilot
 *
 * @package   enrol_coursepilot
 * @copyright 2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_coursepilot;

use advanced_testcase;

/**
 * Class external_test
 *
 * This class extends advanced_testcase and is used for testing purposes
 * within the Moodle enrolment coursepilot plugin.
 *
 *
 * @runTestsInSeparateProcesses
 *
 * @package    enrol_coursepilot
 * @category   test
 * @copyright  2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_test extends advanced_testcase {

    /**
     * @var array $categories An array to store categories.
     * 
     * This protected property holds an array of categories. It is initialized as an empty array.
     */
    protected array $categories = [];

    /**
     * @var int $randomcategories Number of random categories.
     */
    protected int $randomcategories = 5;

    /**
     * Set up method for initializing the test environment.
     *
     * This method is called before each test is executed.
     *
     * @return void
     */
    public function setUp(): void {
        // Set up the test environment.
        $this->setAdminUser();
        $this->resetAfterTest();

        // Initialize the database generator.
        $dg = $this->getDataGenerator();

        // Create 5 course categories.
        for ($i = 1; $i <= 10; $i++) {
            $options = [
                'idnumber' => 'category' . $i,
                'name' => 'Category ' . $i,
                'description' => 'Category ' . $i . ' description',
            ];
            $this->categories[] = $dg->create_category($options);
        }

        // Create 1 Course in each category.
        foreach ($this->categories as $category) {
            $options = [
                'category' => $category->id,
            ];
            $dg->create_course($options);
        }
    }

    /**
     * Set the configuration settings for the coursepilot enrolment plugin.
     *
     * @param array $settings An associative array of settings to configure the external test.
     */
    protected function set_enrol_coursepilot_settings($settings = []) {
        // Set the given configuration settings.
        foreach ($settings as $name => $value) {
            set_config($name, $value, 'enrol_coursepilot');
        }
    }

    /**
     * Test method for retrieving settings of course categories via the external API.
     *
     * This method is designed to test the functionality of getting
     * settings for course categories within the Moodle environment.
     *
     * @return void
     */
    public function test_get_template_categories() {
        // We choose random categories to set as template categories.
        $categories = array_slice($this->categories, 0, $this->randomcategories);
        $strcategories = implode(',', array_column($categories, 'id'));

        // Set the configuration settings for the coursepilot enrolment plugin, disabled first.
        $data = [
            'enable' => 0,
            'templatecategories' => $strcategories
        ];
        $this->set_enrol_coursepilot_settings($data);

        // Retrieve the template categories.
        $templatecategories = external::get_template_categories();

        // Assert that the template categories retrieved are empty.
        $this->assertEmpty($templatecategories);

        // Now we set the configuration settings for the coursepilot enrolment plugin, enabled.
        $data['enable'] = 1;
        $this->set_enrol_coursepilot_settings($data);

        // Retrieve the template categories.
        $templatecategories = external::get_template_categories();

        // Assert that the template categories are retrieved successfully.
        $this->assertCount($this->randomcategories, $templatecategories);

        // Assert that the template categories are correct.
        foreach ($templatecategories as $category) {
            $this->assertContains($category['id'], array_column($categories, 'id'));
        }
    }

}