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

use enrol_goodpay\query;

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
     * @param stdClass $courses
     * @param array  $config
     */
    public function __construct(stdClass $courses) {
        $this->instance = $courses;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output) {
        global $USER;

        profile_load_data($USER);
        ob_start();

        if (get_config(PLUGINNAME, 'status')) {
            return false;
        }

        $courses = $this->instance;

        return [
            'courses' => $courses,
        ];
    }
}
