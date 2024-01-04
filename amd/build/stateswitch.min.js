/**
 * Enrol page JS for Payment plugin
 *
 * @package   local_goodpay_navigation
 * @copyright 2024, elkincp5 <elkincp5@gmail.com>
 * @author    Elkin Chaverra, <elkincp5@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const libs = ["jquery", "core/ajax", "core/notification"];

define(libs, function ($, Ajax, Notification) {
  /**
   * JavaScript functionality for the enrol_goodpay enrol.html page
   */
  var stateswitch = {
    /**
     * Query select UI
     */

    ServiceAjaxRequest: function ({
      method = "",
      body = {},
      then = (data) => {},
      error = (error = "mesagge") => {},
    }) {
      Ajax.call([
        {
          methodname: `enrol_goodpay_${method}`,
          args: body,
          done: function (response) {
            var json = JSON.parse(response);
            if (json?.error) error(json.errormsg);
            if (json?.error) return;
            then(json);
          }.bind(this),
          fail: Notification.exception,
        },
      ]);
    },

    checkDiscountCode: function (args = {}) {
      var self = this;
      self.trThreshold.addClass("d-none");
      self.trDiscountError.addClass("d-none");

      self.ServiceAjaxRequest({
        method: "check_discount",
        body: {
          ...args,
          enrolid: self.instanceid,
          prepaytoken: self.prepaytoken,
        },
        error: function (msg) {
          self.errorFrom(msg, "trDiscountError");
        },
        then: function (res) {
          console.log({ checkDiscountCode: res });
          self.setValueOfObject(res);
          self.statusForm("formDiscountCode", true);
          self.trThreshold.removeClass("d-none");
          // Hide the discount-container.
          self.updateCostView();
        },
      });
    },

    updateCostView: function () {
      var self = this;
    },

    /**
     * Start javascript module with the parameters sent from main.
     */
    init: function (paramts) {
      const self = this;
      const props = JSON.parse(paramts);
    },
  };

  return stateswitch;
});
