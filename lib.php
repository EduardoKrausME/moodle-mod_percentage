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

/**
 * percentage_supports
 *
 * @param string $feature
 * @return bool|string|null
 */
function percentage_supports(string $feature): bool|string|null {
    return match ($feature) {
        FEATURE_MOD_INTRO => true,
        FEATURE_SHOW_DESCRIPTION => true,
        FEATURE_COMPLETION_TRACKS_VIEWS => true,
        FEATURE_BACKUP_MOODLE2 => true,
        FEATURE_MOD_PURPOSE => MOD_PURPOSE_CONTENT,
        default => null,
    };
}

/**
 * percentage_add_instance
 *
 * @param $data
 * @param $mform
 * @return int
 */
function percentage_add_instance($data, $mform = null): int {
    return \mod_percentage\percentage_manager::create($data);
}

/**
 * percentage_update_instance
 *
 * @param $data
 * @param $mform
 * @return bool
 */
function percentage_update_instance($data, $mform = null): bool {
    return \mod_percentage\percentage_manager::update($data);
}

/**
 * percentage_delete_instance
 *
 * @param $id
 * @return bool
 */
function percentage_delete_instance($id): bool {
    return \mod_percentage\percentage_manager::delete((int)$id);
}

/**
 * percentage_pluginfile
 *
 * @param $course
 * @param $cm
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param array $options
 * @return bool
 */
function percentage_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []): bool {
    return \mod_percentage\file_manager::send_intro_file(
        $course,
        $cm,
        $context,
        $filearea,
        $args,
        $forcedownload,
        $options
    );
}
