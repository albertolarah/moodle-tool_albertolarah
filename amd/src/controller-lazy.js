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
 * Controller for the entries table.
 *
 * @module    tool_albertolarah/controller
 * @package   tool_albertolarah
 * @copyright 2018, Alberto Lara Hernández <albertolara@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/str', 'core/notification'], function ($, str, notification) {

    /** @type {Object} The list of selectors for the message area. */
    var SELECTORS = {
        DELETEENTRY: ".delete-entry"
    };

    function Controller(tableid) {
        this.node = $(tableid);
        this._registerEventHandlers();
    }

    /** @type {jQuery} The jQuery node for the table area. */
    Controller.prototype.node = null;

    /**
     * @private
     */
    Controller.prototype._registerEventHandlers = function () {
        this.node.on("click", SELECTORS.DELETEENTRY, this._deleteEntry);
    };


    /**
     * Handles delete an entry.
     *
     * @private
     */
    Controller.prototype._deleteEntry = function (e) {
        e.preventDefault();
        var href = $(e.currentTarget).attr('href');
        str.get_strings([
            {key: 'delete'},
            {key: 'confirmdeletemsg', component: 'tool_albertolarah'},
            {key: 'yes'},
            {key: 'no'}
        ]).done(function (s) {
                notification.confirm(s[0], s[1], s[2], s[3], function () {
                    window.location.href = href;
                });
            }
        ).fail(notification.exception);
    };

    return Controller;
});