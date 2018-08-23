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
define(['jquery', 'core/str', 'core/notification', 'core/ajax', 'core/templates'],
    function ($, str, notification, ajax, templates) {

    /** @type {Object} The list of selectors for the component. */
    var SELECTORS = {
        DELETEENTRY: '.delete-entry',
        TABLEREGION: "[data-region='table-content']"
    };

    /** @type {Object} The list of services used for the component. */
    var SERVICES = {
        DELETEENTRY: 'tool_albertolarah_delete_entry',
        GETENTRIES: 'tool_albertolarah_entries_list'
    };

    function Controller(tableid, courseid) {
        this.node = $(tableid);
        this.courseid = courseid;
        this._registerEventHandlers();
    }

    /** @type {jQuery} The jQuery node for the table area. */
    Controller.prototype.node = null;
    /** @type {int} The course id of the entries table. */
    Controller.prototype.courseid = null;

    /**
     * @private
     */
    Controller.prototype._registerEventHandlers = function () {
        this.node.on("click", SELECTORS.DELETEENTRY, this._deleteEntry.bind(this));
    };


    /**
     * Handles delete an entry.
     *
     * @private
     */
    Controller.prototype._deleteEntry = function (e) {
        e.preventDefault();
        var id = $(e.currentTarget).data('id');
        var deletewscallback = this._callwsdeleteentry.bind(this);
        str.get_strings([
            {key: 'delete'},
            {key: 'confirmdeletemsg', component: 'tool_albertolarah'},
            {key: 'yes'},
            {key: 'no'}
        ]).done(function (s) {
                notification.confirm(s[0], s[1], s[2], s[3], function () {
                    //window.location.href = href;
                    deletewscallback(id);
                });
            }
        ).fail(notification.exception);
    };

    /**
     * Call the WS to delete an entry and retrive the last one.
     * @private
     */
    Controller.prototype._callwsdeleteentry = function (id) {
        var courseid = this.courseid;
        var requests = ajax.call([{
            methodname: SERVICES.DELETEENTRY,
            args: {id: id}
        }, {
            methodname: SERVICES.GETENTRIES,
            args: {courseid: courseid}
        }]);

        requests[1].done(function(data) {
            reloadTable(data);
        }).fail(notification.exception);
    };

    /**
     * Replaces the current table content with the data rendered from template
     * @param {Object} data
     */
    var reloadTable = function(data) {
        templates.render('tool_albertolarah/table_content', data).done(function(html, js) {
            $(SELECTORS.TABLEREGION).replaceWith(html);
            templates.runTemplateJS(js);
        });
    };

    return Controller;
});