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
 * Plugin functions for the local_goodpay_navigation plugin.
 *
 * @package   local_goodpay_navigation
 * @copyright 2024, elkincp5 <elkincp5@gmail.com>
 * @author    Elkin Chaverra, <elkincp5@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__, 3) . '/enrol/goodpay/global.php');
require_once('/global.php');

function itemis_navigation() {
    return array();
}


/**
 * This function extends the navigation with the tool items.
 *
 * @param navigation_node $nav The navigation node to extend
 */
function local_goodpay_navigation_extend_navigation(navigation_node $nav) {

    $pluginname = get_string('pluginname', PLUGINNAME);
    $node = $nav->add($pluginname);
    // Ruta para ir al enrol /enrol/editinstance.php?type=goodpay&courseid=$id
    // Ruta para activar enrol payment /admin/settings.php?section=manageenrols
    // Ruta de configuración plugin /admin/settings.php?section=enrolsettingspayment

    if (is_siteadmin()) {
        $enable = navigation_node::create(
            get_string('goodpayenable', PLUGINNAME),
            new moodle_url(NAV_ENABLE),
            navigation_node::TYPE_CUSTOM,
            'goodpayenable',
            'goodpayenable',
            new pix_icon('i/permissions', '')
        );
        $enable->showinflatnavigation = true;
        $node = $node->add_node($enable);

        $settings = navigation_node::create(
            get_string('goodpaysettings', PLUGINNAME),
            new moodle_url(NAV_SETTINGS),
            navigation_node::TYPE_CUSTOM,
            'goodpeysettings',
            'goodpeysettings',
            new pix_icon('i/permissions', '')
        );
        $settings->showinflatnavigation = true;
        $node = $node->add_node($settings);

        $coupons = navigation_node::create(
            get_string('goodpaycoupon', PLUGINNAME),
            new moodle_url(NAV_COUPON),
            navigation_node::TYPE_CUSTOM,
            'goodpaycoupon',
            'goodpaycoupon',
            new pix_icon('i/permissions', '')
        );
        $coupons->showinflatnavigation = true;
        $node = $node->add_node($coupons);

        $courses = navigation_node::create(
            get_string('goodpaycourses', PLUGINNAME),
            new moodle_url(NAV_ORDER),
            navigation_node::TYPE_CUSTOM,
            'courses',
            'orders',
            new pix_icon('i/report', 'report')
        );
        $courses->showinflatnavigation = true;
        $node->add_node($courses);

        $orders = navigation_node::create(
            get_string('goodpayorder', PLUGINNAME),
            new moodle_url(NAV_ORDER),
            navigation_node::TYPE_CUSTOM,
            'orders',
            'orders',
            new pix_icon('i/report', 'report')
        );
        $orders->showinflatnavigation = true;
        $node->add_node($orders);
    }
}
