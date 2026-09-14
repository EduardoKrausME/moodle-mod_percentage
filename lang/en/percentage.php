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

defined('MOODLE_INTERNAL') || die;

$string['allowedoperations'] = 'Available calculations';
$string['allowedoperations_help'] = 'Choose which calculation types students can use in this activity.';
$string['basevalue_label'] = 'Base value';
$string['calculate'] = 'Calculate';
$string['calculationtype'] = 'Calculation type';
$string['calculatorsettings'] = 'Calculator settings';
$string['cannotdividebyzero'] = 'The reference value cannot be zero for this calculation.';
$string['choosevisualization'] = 'Choose a visualization';
$string['cost_label'] = 'Cost';
$string['decimalplaces'] = 'Maximum decimal places';
$string['decrease_label'] = 'Decrease';
$string['defaultvisualization'] = 'Default visualization';
$string['discountpercentage_label'] = 'Discount';
$string['explanation_margin'] = 'Margin compares profit with the sale price; markup compares profit with cost.';
$string['explanation_percentageof'] = 'Divide the percentage by 100 and multiply it by the value.';
$string['explanation_whatpercentage'] = 'Divide the part by the total and multiply the result by 100.';
$string['final_label'] = 'After';
$string['finalvalue_label'] = 'Final value';
$string['graphicalview'] = 'Graphical view';
$string['increase_label'] = 'Increase';
$string['increasepercentage_label'] = 'Increase';
$string['initialvalue_label'] = 'Initial value';
$string['invalidnumber'] = 'Enter valid numeric values.';
$string['modulename'] = 'Percentage calculator';
$string['modulename_help'] = 'A visual calculator for percentages, changes, discounts, increases, margins and markup.';
$string['modulenameplural'] = 'Percentage calculators';
$string['of_total_100'] = 'of a total of 100%.';
$string['operation_change'] = 'Percentage change';
$string['operation_discount'] = 'Discount';
$string['operation_increase'] = 'Increase';
$string['operation_margin'] = 'Margin and markup';
$string['operation_percentageof'] = 'Percentage of a value';
$string['operation_whatpercentage'] = 'What percentage?';
$string['original_label'] = 'Before';
$string['out_of_100'] = 'out of every 100.';
$string['part_label'] = 'Part';
$string['percentage:addinstance'] = 'Add a new percentage calculator';
$string['percentage:view'] = 'View percentage calculator';
$string['percentage_label'] = 'Percentage';
$string['percentagename'] = 'Activity name';
$string['pluginadministration'] = 'Percentage administration';
$string['pluginname'] = 'Percentage calculator';
$string['privacy:metadata'] = 'The Percentage calculator activity does not store personal data.';
$string['profit_label'] = 'Profit';
$string['question_change'] = 'What was the percentage change between two values?';
$string['question_discount'] = 'Apply a percentage discount.';
$string['question_increase'] = 'Apply a percentage increase.';
$string['question_margin'] = 'Calculate margin, markup and profit.';
$string['question_percentageof'] = 'How much is a percentage of a value?';
$string['question_whatpercentage'] = 'What percentage does one value represent of another?';
$string['result'] = 'Result';
$string['result_change'] = 'The variation compares the difference with the initial value.';
$string['result_discount'] = 'The percentage is subtracted from the original value.';
$string['result_increase'] = 'The percentage is added to the original value.';
$string['result_margin'] = 'Margin';
$string['result_markup'] = 'Markup';
$string['result_percentageof'] = 'Percentage: ';
$string['result_profit'] = 'Profit';
$string['result_whatpercentage'] = 'is this percentage of';
$string['resultwaitingtext'] = 'The result and visual representation will appear here.';
$string['resultwaitingtitle'] = 'Enter the values';
$string['sale_label'] = 'Sale price';
$string['showexplanation'] = 'Show short explanation';
$string['showformula'] = 'Show calculation formula';
$string['total_label'] = 'Total';
$string['understandpercentage'] = 'See the percentage';
$string['value_label'] = 'Value';
$string['visualization_auto'] = 'Automatic';
$string['visualization_bar'] = 'Bar';
$string['visualization_comparison'] = 'Before and after';
$string['visualization_donut'] = 'Donut';
$string['visualization_grid'] = '100-square grid';
