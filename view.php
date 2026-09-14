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

require_once(__DIR__ . "/../../config.php");

$id = required_param("id", PARAM_INT);
$cm = get_coursemodule_from_id("percentage", $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$percentage = $DB->get_record("percentage", ["id" => $cm->instance], "*", MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability("mod/percentage:view", $context);

$PAGE->set_url("/mod/percentage/view.php", ["id" => $cm->id]);
$PAGE->set_title(format_string($percentage->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$event = \mod_percentage\event\course_module_viewed::create([
    "objectid" => $percentage->id,
    "context" => $context,
]);
$event->add_record_snapshot("course", $course);
$event->add_record_snapshot("percentage", $percentage);
$event->trigger();

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$templatecontext = \mod_percentage\view_model::build($percentage);

$PAGE->requires->js_call_amd("mod_percentage/calculator", "init", [[
    "visualization" => $percentage->visualization,
    "decimalplaces" => (int)$percentage->decimalplaces,
    "showformula" => !empty($percentage->showformula),
    "showexplanation" => !empty($percentage->showexplanation),
]]);

$PAGE->requires->strings_for_js([
    "calculate",
    "invalidnumber",
    "cannotdividebyzero",
    "result_percentageof",
    "result_whatpercentage",
    "result_change",
    "result_increase",
    "result_discount",
    "result_margin",
    "result_markup",
    "result_profit",
    "explanation_percentageof",
    "explanation_whatpercentage",
    "explanation_margin",
    "of_total_100",
    "out_of_100",
    "increase_label",
    "decrease_label",
    "original_label",
    "final_label",
    "cost_label",
    "sale_label",
    "profit_label",
], "mod_percentage");

$PAGE->add_body_class("mod-percentage-page");

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($percentage->name));

if (trim((string)$percentage->intro) !== "") {
    echo $OUTPUT->box(format_module_intro("percentage", $percentage, $cm->id), "generalbox mod_introbox");
}

echo $OUTPUT->render_from_template("mod_percentage/calculator", $templatecontext);
echo $OUTPUT->footer();
