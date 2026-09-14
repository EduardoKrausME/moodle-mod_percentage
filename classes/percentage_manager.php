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
 * Percentage calculator.
 *
 * @package   mod_percentage
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_percentage;

/**
 * Class percentage_manager.
 */
class percentage_manager {
    /**
     * Method create.
     *
     * @param object $data Parameter data.
     * @return int Return value.
     */
    public static function create(object $data): int {
        global $DB;

        self::normalise($data);
        $data->timecreated = time();
        $data->timemodified = $data->timecreated;

        return $DB->insert_record("percentage", $data);
    }

    /**
     * Method update.
     *
     * @param object $data Parameter data.
     * @return bool Return value.
     */
    public static function update(object $data): bool {
        global $DB;

        $data->id = $data->instance;
        self::normalise($data);
        $data->timemodified = time();

        return $DB->update_record("percentage", $data);
    }

    /**
     * Method delete.
     *
     * @param int $id Parameter id.
     * @return bool Return value.
     */
    public static function delete(int $id): bool {
        global $DB;

        if (!$DB->record_exists("percentage", ["id" => $id])) {
            return false;
        }

        $DB->delete_records("percentage", ["id" => $id]);
        return true;
    }

    /**
     * Method normalise.
     *
     * @param object $data Parameter data.
     * @return void Return value.
     */
    private static function normalise(object $data): void {
        if (isset($data->allowedoperations) && is_array($data->allowedoperations)) {
            $allowed = array_intersect(
                array_keys(calculation_catalog::operations()),
                array_map("strval", $data->allowedoperations)
            );
            $data->allowedoperations = implode(",", $allowed);
        }

        if (empty($data->allowedoperations)) {
            $data->allowedoperations = implode(",", array_keys(calculation_catalog::operations()));
        }

        $data->visualization = calculation_catalog::is_valid_visualization($data->visualization ?? "auto")
            ? $data->visualization
            : "auto";

        $data->decimalplaces = min(6, max(0, (int)($data->decimalplaces ?? 2)));
        $data->showformula = empty($data->showformula) ? 0 : 1;
        $data->showexplanation = empty($data->showexplanation) ? 0 : 1;
    }
}
