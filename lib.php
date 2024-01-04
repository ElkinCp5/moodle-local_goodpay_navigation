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
require_once(dirname(__FILE__) . '/global.php');

function itemis_navigation() {
    return array();
}


/**
 * This function extends the navigation with the tool items.
 *
 * @param navigation_node $nav The navigation node to extend
 */
function local_goodpay_navigation_extend_navigation(navigation_node $nav) {

    $pluginname = get_string('pluginname', LOCAL_PLUGINNAME);
    $node = $nav->add($pluginname);

    if (is_siteadmin()) {
        $home = navigation_node::create(
            $pluginname,
            new moodle_url(NAV_HOME),
            navigation_node::TYPE_CUSTOM,
            'home',
            'home',
            new pix_icon('i/permissions', '')
        );
        $home->showinflatnavigation = true;
        $node = $node->add_node($home);

        $coupons = navigation_node::create(
            get_string('goodpaycoupon', LOCAL_PLUGINNAME),
            new moodle_url(NAV_COUPON),
            navigation_node::TYPE_CUSTOM,
            'coupons',
            'coupons',
            new pix_icon('i/permissions', '')
        );
        $coupons->showinflatnavigation = true;
        $node = $node->add_node($coupons);

        $orders = navigation_node::create(
            get_string('goodpayorder', LOCAL_PLUGINNAME),
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
