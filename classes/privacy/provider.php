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
 * Defines {@link \local_customization\privacy\provider} class.
 *
 * @package     local_customization
 * @category    privacy
 * @copyright   2020 David Ordonez <davidherzlos@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_customization\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\helper;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;

/**
 * Privacy API implementation for the customization plugin.
 *
 * @copyright  2020 David Ordonez <davidherzlos@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\plugin\provider,
        \core_privacy\local\request\user_preference_provider {

    /**
     * Describe all the places where the customization plugin stores some personal data.
     *
     * @param collection $collection Collection of items to add metadata to.
     * @return collection Collection with our added items.
     */
    public static function get_metadata(collection $collection) : collection {

        $collection->add_database_table('local_customization_confirmation', [
           'userid' => 'privacy:metadata:db:confirmation:userid',
           'timecreated' => 'privacy:metadata:db:confirmation:timecreated',
           'timemodified' => 'privacy:metadata:db:confirmation:timemodified',
        ], 'privacy:metadata:db:confirmation');

        $collection->add_user_preference('emailnotification', 'privacy:metadata:preference:emailnotification');

        $collection->add_subsystem_link('core_files', [], 'privacy:metadata:subsystem:files');

        return $collection;
    }

    /**
     * Get the list of contexts that contain personal data for the specified user.
     *
     * @param int $userid ID of the user.
     * @return contextlist List of contexts containing the user's personal data.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {

        $contextlist = new contextlist();

        // You will probably implement something using `$contextlist->add_from_sql()` here. See examples in other plugins.

        return $contextlist;
    }

    /**
     * Export personal data stored in the given contexts.
     *
     * @param approved_contextlist $contextlist List of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (!count($contextlist)) {
            return;
        }

        $user = $contextlist->get_user();

        // You will probably implement something using writer's methods `export_data()`, `export_area_files()` etc.
        // The following code is just a dummy example.

        foreach ($contextlist->get_contexts() as $context) {
            $data = helper::get_context_data($context, $user);
            $data->implemented = transform::yesno(false);
            $data->todo = 'Not implemented yet.';
            writer::with_context($context)->export_data([], $data);
        }
    }

    /**
     * Delete personal data for all users in the context.
     *
     * @param context $context Context to delete personal data from.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        // You will probably use some variant of `$DB->delete_records()` here to remove user data from your tables.
        // If you have plugin files, do not forget to clean the relevant files areas too.
    }

    /**
     * Delete personal data for the user in a list of contexts.
     *
     * @param approved_contextlist $contextlist List of contexts to delete data from.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (!count($contextlist)) {
            return;
        }

        list($contextsql, $contextparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);
        $user = $contextlist->get_user();
        $fs = get_file_storage();

        // You will probably use some variant of `$DB->delete_records()` here to remove user data from your tables.
        // If you have plugin files, do not forget to clean the relevant files areas too.
    }

    /**
     * Export all user preferences controlled by this plugin.
     *
     * @param int $userid ID of the user we are exporting data for
     */
    public static function export_user_preferences(int $userid) {

        $emailnotification = get_user_preferences('emailnotification', null, $userid);

        if ($emailnotification !== null) {
            writer::export_user_preference('local_customization', 'emailnotification', $emailnotification,
                get_string('privacy:metadata:preference:emailnotification', 'local_customization'));
        }
    }
}
