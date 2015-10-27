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
 * @package    widget_checkboxlist
 * @copyright  2015 Dunlop-Lello Consulting LTD
 * @author     Phil Lello <phil@dunlop-lello.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(dirname(__DIR__)).'/lib.php');

class widget_checkboxlist_widget extends widget_input
{
    public function is_configuration_legal($configuration)
    {
        return is_array($configuration);
    }

    public function is_value_legal($value)
    {
        return is_array($value);
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
        $output .= html_writer::start_tag('ul', $this->attributes);
        foreach ($this->configuration as $value => $description)
        {
            $output .= html_writer::start_tag('li');
            $attrs = array('type' => 'checkbox', 'value' => $value);
            if (in_array($value, $this->value))
            {
                $attrs['checked'] = 'checked';
            }
            if ($name !== null)
            {
                $attrs['name'] = $name.'[]';
            }
            $output .= html_writer::empty_tag('input', $attrs);
            $output .= format_string($description);
            $output .= html_writer::end_tag('li');
        }
        $output .= html_writer::end_tag('ul');
        return $output;
    }

    public static function default_value()
    {
        return array();
    }

    public static function sample_config()
    {
        return array('Zero', 'One', 'Two');
    }

    public static function sample_value()
    {
        return array(1);
    }

    public static function describe_value($config, $value)
    {
        $result = array();
        foreach ($value as $v)
        {
            $result[] = $config[$v];
        }
        $result = implode($result, ",");
        return $result;
    }
}
