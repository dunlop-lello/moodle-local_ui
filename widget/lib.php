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
 * @package    widget
 * @copyright  2015 Dunlop-Lello Consulting LTD
 * @author     Phil Lello <phil@dunlop-lello.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/pear/HTML/QuickForm/element.php');

/**
 * This interface exists solely because PHP doesn't like abstract static methods.
 */
interface widget_interface
{
    static function sample_config();
}

abstract class widget_base implements widget_interface
{
    /** @var $attributes HTML attributes for outermost tag. */
    protected $attributes = array();

    /** @var $configuration The widget configuration. */
    protected $configuration = null;

    public function __construct($configuration, $attributes = array())
    {
        if (!$this->is_configuration_legal($configuration))
        {
            throw new coding_exception("Illegal configuration.");
        }
        $this->configuration = $configuration;
        $this->add_attributes($attributes);
    }

    public function add_attributes($attributes)
    {
        foreach ($attributes as $k => $value)
        {
            // TODO: Decide if 'class' attribute needs special handling.
            $this->attributes[$k] = $value;
        }
    }

    final public function render($renderer)
    {
        $widgetname = preg_replace('/^widget_(.+)_widget$/', '${1}', get_called_class());
        $method = 'render_widget_'.$widgetname;
        if (method_exists($renderer, $method))
        {
            return $renderer->$method($widget);
        }
        else
        {
            return $this->default_render($renderer);
        }
    }

    abstract protected function default_render($renderer);

    public static function html($configuration, $attributes = array())
    {
        global $OUTPUT;

        $widgetname = preg_replace('/^widget_(.+)_widget$/', '${1}', get_called_class());
        $klass = "widget_{$widgetname}_widget";
        $widget = new $klass($configuration);
        $widget->add_attributes($attributes);
        return $widget->render($OUTPUT);
    }
}

/**
 * This interface exists solely because PHP doesn't like abstract static methods.
 */
interface widget_input_interface
{
    static function default_value();
    static function sample_value();
    static function describe_value($config, $value);
}

abstract class widget_input extends widget_base implements widget_input_interface
{
    /** @var $value The widget value. */
    protected $value = null;

    public static function adminsetting($setting, $name, $description, $configuration, $default = null)
    {
        $widgetname = preg_replace('/^widget_(.+)_widget$/', '${1}', get_called_class());
        $klass = "widget_{$widgetname}_adminsetting";
        if (class_exists($klass))
        {
            return new $klass($setting, $name, $description, $configuration, $default);
        }
        else
        {
            return new widget_adminsetting($widgetname, $setting, $name, $description, $configuration, $default);
        }
    }

    public static function quickformelement($name, $visiblename, $configuration, $attributes='')
    {
        $widgetname = preg_replace('/^widget_(.+)_widget$/', '${1}', get_called_class());
        $klass = "widget_{$widgetname}_quickformelement";
        if (class_exists($klass))
        {
            return new $klass($name, $visiblename, $configuration, $attributes);
        }
        else
        {
            return new widget_quickformelement($widgetname, $name, $visiblename, $configuration, $attributes);
        }
    }

    public function add_attributes($attributes)
    {
        if (isset($attributes['value']))
        {
            $this->set_value($attributes['value']);
            unset($attributes['value']);
        }
        parent::add_attributes($attributes);
    }

    public function set_value($value)
    {
        if (!$this->is_value_legal($value))
        {
            throw new coding_exception("Illegal value '$value'.");
        }
        $this->value = $value;
    }
}

class widget_adminsetting extends admin_setting
{
    /** @var string $widgetname The widget type. */
    protected $widgetname = null;

    /** @var $configuration The widget configuration. */
    protected $configuration = null;

    public function __construct($widgetname, $name, $visiblename, $description, $configuration, $defaultsetting)
    {
        parent::__construct($name, $visiblename, $description, $defaultsetting);
        $this->widgetname = $widgetname;
        $this->configuration = $configuration;
    }

    public function get_setting()
    {
        $data = $this->config_read($this->name);
        if ($data !== null) {
            return json_decode($data, true);
        }
        return $this->get_defaultsetting();
    }

    public function write_setting($data)
    {
        $this->config_write($this->name, json_encode($data));
    }

    public function output_html($data, $query = '')
    {
        $klass = 'widget_'.$this->widgetname.'_widget';
        $default = $klass::describe_value($this->configuration, $this->get_defaultsetting());
        $widgethtml = $klass::html($this->configuration, array('name' => $this->name, 'value' => $this->get_setting()));
        return format_admin_setting($this, $this->visiblename, $widgethtml, $this->description, true, '', $default, $query);
    }
}

class widget_quickformelement extends HTML_QuickForm_element
{
    /** @var string $widgetname The widget type. */
    protected $widgetname = null;

    /** @var $configuration The widget configuration. */
    protected $configuration = null;

    /** @var $value The widget value. */
    protected $value = null;

    public function __construct($widgetname, $name, $visiblename, $configuration, $attributes)
    {
        $this->widgetname = $widgetname;
        $this->configuration = $configuration;
        parent::__construct($name, $visiblename, $attributes);

        $klass = 'widget_'.$this->widgetname.'_widget';
        $this->value = $klass::default_value();
    }

    public function toHtml()
    {
        $klass = 'widget_'.$this->widgetname.'_widget';
        $attr = $this->getAttributes();
        $attr['value'] = $this->getValue();
        $widgethtml = $klass::html($this->configuration, $attr);
        return $this->_getTabs() . $widgethtml;
    }

    public function getName()
    {
        return $this->getAttribute('name');
    }

    public function setName($name)
    {
        $this->updateAttributes(array('name' => $name));
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}
