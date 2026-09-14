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
 * Class view_model.
 */
class view_model {
    /**
     * Method build.
     *
     * @param object $percentage Parameter percentage.
     * @return array Return value.
     */
    public static function build(object $percentage): array {
        return [
            "operations" => calculation_catalog::selected_operations($percentage->allowedoperations),
            "visualization" => $percentage->visualization,
            "showformula" => !empty($percentage->showformula),
            "showexplanation" => !empty($percentage->showexplanation),
            "decimalplaces" => (int)$percentage->decimalplaces,
            "visualizations" => self::visualization_buttons(),
        ];
    }

    /**
     * Method visualization_buttons.
     *
     * @return array Return value.
     */
    private static function visualization_buttons(): array {
        $items = [];
        foreach (calculation_catalog::visualizations() as $key => $label) {
            if ($key === "auto") {
                continue;
            }
            $items[] = [
                "key" => $key,
                "label" => $label,
            ];
        }
        return $items;
    }
}
