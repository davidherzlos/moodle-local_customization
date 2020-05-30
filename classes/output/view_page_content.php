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
 * Renderplatable to prepare output of local_customization.
 *
 * @package     local_customization
 * @copyright   2020 David Ordonez <davidherzlos@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_customization\output;



defined('MOODLE_INTERNAL') || die();
/**
 * Renderpletable for local_customization
 */
class view_page_content implements \renderable, \templatable {

    protected $data;

    function __construct($instance) {
        $this->data = new \stdClass();
        $this->data->instance = $instance;
    }

    public function export_for_template(\renderer_base $output) {
        return $this->data;
    }
}








