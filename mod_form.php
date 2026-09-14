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

require_once("{$CFG->dirroot}/course/moodleform_mod.php");

/**
 * Class mod_percentage_mod_form.
 */
class mod_percentage_mod_form extends moodleform_mod {
    /**
     * Method definition.
     *
     * @return mixed Return value.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement("header", "general", get_string("general", "form"));
        $mform->addElement("text", "name", get_string("percentagename", "mod_percentage"), ["size" => 64]);
        $mform->setType("name", PARAM_TEXT);
        $mform->addRule("name", null, "required", null, "client");

        $this->standard_intro_elements();

        $mform->addElement("html", html_writer::tag("h3", get_string("calculatorsettings", "mod_percentage")));
        $mform->addElement(
            "select",
            "allowedoperations",
            get_string("allowedoperations", "mod_percentage"),
            \mod_percentage\calculation_catalog::operations(),
            ["multiple" => "multiple", "size" => 6]
        );
        $mform->addHelpButton("allowedoperations", "allowedoperations", "mod_percentage");
        $mform->setDefault("allowedoperations", array_keys(\mod_percentage\calculation_catalog::operations()));

        $mform->addElement(
            "select",
            "visualization",
            get_string("defaultvisualization", "mod_percentage"),
            \mod_percentage\calculation_catalog::visualizations()
        );
        $mform->setDefault("visualization", "auto");

        $decimals = array_combine(range(0, 6), range(0, 6));
        $mform->addElement("select", "decimalplaces", get_string("decimalplaces", "mod_percentage"), $decimals);
        $mform->setDefault("decimalplaces", 2);

        $mform->addElement("advcheckbox", "showformula", get_string("showformula", "mod_percentage"));
        $mform->setDefault("showformula", 1);
        $mform->addElement("advcheckbox", "showexplanation", get_string("showexplanation", "mod_percentage"));
        $mform->setDefault("showexplanation", 1);

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    /**
     * Method data_preprocessing.
     *
     * @param mixed $defaultvalues Parameter defaultvalues.
     * @return mixed Return value.
     */
    public function data_preprocessing(&$defaultvalues) {
        parent::data_preprocessing($defaultvalues);

        if (!empty($defaultvalues["allowedoperations"]) && is_string($defaultvalues["allowedoperations"])) {
            $defaultvalues["allowedoperations"] = explode(",", $defaultvalues["allowedoperations"]);
        }
    }
}
