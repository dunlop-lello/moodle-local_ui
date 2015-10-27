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
 * @package    widget_grid
 * @copyright  2015 Dunlop-Lello Consulting LTD
 * @author     Phil Lello <phil@dunlop-lello.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(dirname(__DIR__)).'/lib.php');

class widget_checkboxgrid_widget extends widget_grid_widget
{
    public function render_cell($name, $value)
    {
        $attrs = array('type' => 'checkbox', 'value' => '1');
        if ($value)
        {
            $attrs['checked'] = 'checked';
        }
        if ($name !== null)
        {
            $attrs['name'] = $name;
        }
        return html_writer::empty_tag('input', $attrs);
    }

    public static function sample_value()
    {
        return array(array(1));
    }

    public static function describe_value($config, $value)
    {
        $result = array();
        foreach ($config->rows as $rowkey => $rowdescription)
        {
            $rowresult = array();
            foreach ($config->columns as $columnkey => $columndescription)
            {
                if (isset($value[$rowkey][$columnkey]))
                {
                    $rowresult[] = $columndescription;
                }
            }
            if (count($rowresult))
            {
                $result[] = $rowdescription.": ".implode($rowresult, ", ");
            }
        }
        $result = implode($result, ". ").".";
        return $result;
    }
}
