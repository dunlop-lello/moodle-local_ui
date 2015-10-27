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

abstract class widget_grid_widget extends widget_input
{
    public function is_configuration_legal($configuration)
    {
        return ($configuration instanceof model_grid_config);
    }

    public function is_value_legal($value)
    {
        return ($value instanceof model_grid_data);
    }

    public function default_render($renderer)
    {
        $name = null;
        $attributes = $this->attributes;
        if (isset($attributes['name']))
        {
            $name = $attributes['name'];
            unset($attributes['name']);
        }

        $output  = '';
        $output .= html_writer::start_tag('table', $this->attributes);
        $output .= html_writer::start_tag('thead');
        $output .= html_writer::start_tag('tr');
        $output .= html_writer::empty_tag('th');
        foreach ($this->configuration->columns as $columnkey => $columndescription)
        {
            $output .= html_writer::tag('th', format_string($columndescription));
        }
        $output .= html_writer::end_tag('tr');
        $output .= html_writer::end_tag('thead');
        $output .= html_writer::start_tag('tbody');
        foreach ($this->configuration->rows as $rowkey => $rowdescription)
        {
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('th', format_string($rowdescription));
            foreach ($this->configuration->columns as $columnkey => $columndescription)
            {
                $value = $this->value->get($rowkey, $columnkey);
                $output .= html_writer::start_tag('td');
                $output .= $this->render_cell("{$name}[$rowkey][$columnkey]", $value);
                $output .= html_writer::end_tag('td');
            }
            $output .= html_writer::end_tag('tr');
        }
        $output .= html_writer::end_tag('tbody');
        $output .= html_writer::end_tag('table');
        return $output;
    }

    abstract public function render_cell($name, $value);

    public function set_value($value)
    {
        if (is_array($value) && is_array(reset($value)))
        {
            $value = new model_grid_data($value);
        }
        if (!$value instanceof model_grid_data)
        {
            throw new coding_exception("Illegal value '$value'.");
        }
        $this->value = $value;
    }

    public static function default_value()
    {
        return array(array());
    }

    public static function sample_config()
    {
        return new model_grid_config(
            array('Row 0', 'Row 1', 'Row 2', 'Row 3'),
            array('Col 0', 'Col 1', 'Col 2', 'Col 3')
        );
    }
}
