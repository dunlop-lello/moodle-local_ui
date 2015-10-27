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
 * Version details
 *
 * @package    model_grid
 * @copyright  2015 Dunlop-Lello Consulting LTD
 * @author     Phil Lello <phil@dunlop-lello.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class model_grid_config {
    public $rows = array();
    public $columns = array();
    public $rowcaption = null;
    public $columncaption = null;

    public function __construct($rows, $columns, $rowcaption = null, $columncaption = null)
    {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->rowcaption = $rowcaption;
        $this->columncaption = $columncaption;
    }
}
