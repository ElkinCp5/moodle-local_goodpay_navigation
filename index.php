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
 * Cart page.
 *
 * @package   local_goodpay_navigation
 * @copyright 2024, elkincp5 <elkincp5@gmail.com>
 * @author    Elkin Chaverra, <elkincp5@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__, 3) . "/config.php");
require_once(dirname(__FILE__) . "/global.php");

require_login();

// Set up URL and page context.
$pluginname = get_string('pluginname', LOCAL_PLUGINNAME);
$url = new moodle_url("/");

// Set up SQL query to retrieve order details.
$faeye = '<i class="fa fa-eye" aria-hidden="true"></i>';
$faeyeslash = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
$sql = "SELECT c.id,
               c.category,
               CASE WHEN c.visible = 1 THEN 'visible' ELSE 'hidden' END AS visible,
               CASE WHEN c.visible = 1 THEN '$faeye' ELSE '$faeyeslash' END AS icon,
               c.fullname,
               GROUP_CONCAT(e.enrol SEPARATOR ', ') AS enrol,
               cc.name
          FROM {course} c
          JOIN {enrol} e
            ON e.courseid = c.id
          JOIN {course_categories} cc
            ON cc.id = c.category
      GROUP BY c.id, c.category, c.visible, c.fullname, cc.name";

// Get the details for the specified order from the database.
$courses = $DB->get_records_sql($sql);

$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_title($pluginname);
$PAGE->set_heading(get_string('setting', LOCAL_PLUGINNAME));

// Get the page renderer and apply required CSS.
$output = $PAGE->get_renderer(LOCAL_PLUGINNAME);
$render = new \local_goodpay_navigation\output\main($courses);

// Output the page header and render the main content.
echo $output->header();
echo $output->render($render);
echo $output->footer();
