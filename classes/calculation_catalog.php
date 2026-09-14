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
 * Class calculation_catalog.
 */
class calculation_catalog {
    /**
     * Method operations.
     *
     * @return array Return value.
     */
    public static function operations(): array {
        return [
            "percentageof" => get_string("operation_percentageof", "mod_percentage"),
            "whatpercentage" => get_string("operation_whatpercentage", "mod_percentage"),
            "change" => get_string("operation_change", "mod_percentage"),
            "increase" => get_string("operation_increase", "mod_percentage"),
            "discount" => get_string("operation_discount", "mod_percentage"),
            "margin" => get_string("operation_margin", "mod_percentage"),
        ];
    }

    /**
     * Method visualizations.
     *
     * @return array Return value.
     */
    public static function visualizations(): array {
        return [
            "auto" => get_string("visualization_auto", "mod_percentage"),
            "bar" => get_string("visualization_bar", "mod_percentage"),
            "donut" => get_string("visualization_donut", "mod_percentage"),
            "grid" => get_string("visualization_grid", "mod_percentage"),
            "comparison" => get_string("visualization_comparison", "mod_percentage"),
        ];
    }

    /**
     * Method is_valid_visualization.
     *
     * @param string $visualization Parameter visualization.
     * @return bool Return value.
     */
    public static function is_valid_visualization(string $visualization): bool {
        return array_key_exists($visualization, self::visualizations());
    }

    /**
     * Method selected_operations.
     *
     * @param string $stored Parameter stored.
     * @return array Return value.
     */
    public static function selected_operations(string $stored): array {
        $selected = array_filter(array_map("trim", explode(",", $stored)));
        $available = self::operations();
        $result = [];

        foreach ($selected as $operation) {
            if (isset($available[$operation])) {
                $result[] = [
                    "key" => $operation,
                    "label" => $available[$operation],
                    "first" => empty($result),
                ];
            }
        }

        if (!$result) {
            foreach ($available as $key => $label) {
                $result[] = [
                    "key" => $key,
                    "label" => $label,
                    "first" => empty($result),
                ];
            }
        }

        return $result;
    }
}
