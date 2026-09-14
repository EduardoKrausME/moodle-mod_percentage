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
 * Class file_manager.
 */
class file_manager {
    /**
     * Method send_intro_file.
     *
     * @param \stdClass $course Parameter course.
     * @param \stdClass $cm Parameter cm.
     * @param \context_module $context Parameter context.
     * @param string $filearea Parameter filearea.
     * @param array $args Parameter args.
     * @param bool $forcedownload Parameter forcedownload.
     * @param array $options Parameter options.
     * @return bool Return value.
     */
    public static function send_intro_file(
        \stdClass $course,
        \stdClass $cm,
        \context_module $context,
        string $filearea,
        array $args,
        bool $forcedownload,
        array $options
    ): bool {
        if ($filearea !== "intro") {
            return false;
        }

        require_course_login($course, true, $cm);
        require_capability("mod/percentage:view", $context);

        $itemid = (int)array_shift($args);
        if ($itemid !== 0) {
            return false;
        }

        $relativepath = implode("/", $args);
        $fullpath = "/{$context->id}/mod_percentage/intro/0/{$relativepath}";
        $fs = get_file_storage();
        $file = $fs->get_file_by_hash(sha1($fullpath));

        if (!$file || $file->is_directory()) {
            return false;
        }

        send_stored_file($file, 0, 0, $forcedownload, $options);
        return true;
    }
}
