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
 * Javasecript for the action column.
 *
 * @module   friendly_url 
 */

define(['jquery', 'core/modal_factory', 'core/str', 'core/modal_events', 'core/ajax', 'core/notification'], function($,
                                                                                                                     ModalFactory,
                                                                                                                     String,
                                                                                                                     ModalEvents,
                                                                                                                     Ajax,
                                                                                                                     Notification
) {
    var trigger = $('.local_alias_delete_button');
    ModalFactory.create({
        type: ModalFactory.types.SAVE_CANCEL,
        title: String.get_string('delete_alias_title', 'local_alias'),
        body: String.get_string('delete_alias', 'local_alias'),
        preShowCallback: function(triggerElement, modal) {
            triggerElement = $(triggerElement);
            let classString = triggerElement[0].classList[0];
            let aliasid = classString.substr(classString.lastIndexOf('local_aliasid') + 'local_aliasid'.length);
            modal.params = {'aliasid': aliasid};
            modal.setSaveButtonText(String.get_string('delete_alias_button', 'local_alias'));
        },
        large: true
    }, trigger)
        .done(function(modal) {
            modal.getRoot().on(ModalEvents.save, function(e) {
                e.preventDefault();
                let footer = yield.one('.modal-footer');
                footer.setContent('Deleting...');
                let spinner = M.util.add_spinner(Y, footer);
                spinner.show();
                let request = {
                    methodname: 'local_alias_delete_alias',
                    args: modal.params,
                };
                Ajax.call([request])[0].done(function(data) {
                    spinner.hide();
                    if(data == true) {
                        window.location.reload();
                    } else {
                        Notification.addNotification({
                            message: String.get_string('err_delete_alias_failed', 'local_alias'),
                            type: 'error'
                        });
                    }
                }).fail(Notification.exception);
            });
        });
});