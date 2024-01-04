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
 * Main render class.
 *
 * @package   local_goodpay_navigation
 * @copyright 2024 elkincp5, <elkincp5@gmail.com>
 * @author    Elkin Chaverra, <elkincp5@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_goodpay_navigation\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;
use moodle_url;

use enrol_goodpay\helper;
use \core_course\external\course_summary_exporter;
use \core\plugininfo\enrol;

require_once("$CFG->dirroot/lib/enrollib.php");

/**
 * Renderable main class.
 *
 * @package   enrol_goodpay
 * @copyright 2024 elkincp5, <elkincp5@gmail.com>
 * @author    Elkin Chaverra, <elkincp5@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class main implements renderable, templatable {

    private $instance;

    /**
     * Constructor.
     *
     * @param array $courses
     */
    public function __construct(array $courses) {
        $this->instance = $courses;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output) {
        global $USER, $SESSION, $DB;

        profile_load_data($USER);
        ob_start();

        $config = $DB->get_field('config', 'value', array('name' => 'enrol_plugins_enabled'));
        $status = helper::strpos($config, NAME);
        $home = new moodle_url('/');
        $statustext = 'disable';
        $icon = 'fa-eye';
        $button = 'primary';

        $courses = array();

        foreach ($this->instance as $course) {
            helper::get_course_image($course);
            $enrolments = array();
            $enrolnames = explode(',', $course->enrol);
            $enrolurl = new moodle_url('/enrol/editinstance.php', array('courseid' => $course->id, 'type' => NAME));
            $enrolurl = helper::url_clear($enrolurl);

            if (is_array($enrolnames)) {
                foreach ($enrolnames as $name) {
                    $has = ($name == NAME) && $status;
                    $setting = get_config(LOCAL_PLUGINNAME, 'setting');
                    array_push($enrolments, array(
                        'name' => $has ? "$setting: $name" : $name,
                        'url' => $has ? $enrolurl : '',
                    ));
                }
            }

            $course->enrolurl = $enrolurl;
            $course->hasgoodpay = helper::strpos($course->enrol, NAME) && $status;
            $course->enrolments = $enrolments;

            array_push($courses, $course);
        }

        if (!$status) {
            $statustext = 'enable';
            $icon = 'fa-eye-slash';
            $button = 'secondary';
        }

        $links = array(
            array(
                "name" => get_string('setting', LOCAL_PLUGINNAME),
                "url" => helper::url_clear(new moodle_url(NAV_SETTINGS))
            ),
            array(
                "name" => get_string('coupon', LOCAL_PLUGINNAME),
                "url" => helper::url_clear(new moodle_url(NAV_COUPON))
            ),
            array(
                "name" => get_string('order', LOCAL_PLUGINNAME),
                "url" => helper::url_clear(new moodle_url(NAV_ORDER))
            ),
        );

        $parms = array('sesskey' => sesskey(), 'action' => $statustext, 'enrol' => 'goodpay');
        $action = array(
            "name" => get_string($statustext, LOCAL_PLUGINNAME),
            "icon" => "<i class='fa $icon mr-2' aria-hidden='true'></i>",
            "url" => helper::url_clear(new moodle_url('/admin/enrol.php', $parms)),
            "class" => $button
        );

        return [
            'home' => $home,
            'links' => $links,
            'action' => $action,
            'status' => $status,
            'courses' => $courses,
            'hascourses' => (is_array($courses) && count($courses)),
        ];
    }
}
