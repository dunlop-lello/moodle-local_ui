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
 * @package    local_ui
 * @copyright  2015 Dunlop-Lello Consulting LTD
 * @author     Phil Lello <phil@dunlop-lello.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir.'/formslib.php');

class test_form extends moodleform
{
    public function definition()
    {
        $form = &$this->_form;
        $klass = $this->_customdata['klass'];
        $config = $this->_customdata['config'];

        $element = $klass::quickformelement('test_'.$klass, 'name', $config);
        $form->addElement($element);

        $this->add_action_buttons(false);
    }
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/ui/widget/checkboxlist/example.php');

echo $OUTPUT->header();

$widgets = core_component::get_plugin_list('widget');
foreach ($widgets as $widget => $path)
{
    try
    {
        $klass = 'widget_'.$widget.'_widget';

        // Warn on broken plugins (missing widget class).
        if (!class_exists($klass))
        {
            throw new coding_exception("class $klass does not exist!");
        }

        // Skip abstract classes.
        $rc = new ReflectionClass($klass);
        if ($rc->isAbstract())
        {
            continue;
        }

        echo html_writer::tag('h1', get_string('pluginname', 'widget_'.$widget));

        $config = $klass::sample_config();
        $value = $klass::sample_value();

        echo html_writer::tag('h2', 'HTML');
        echo $klass::html($config, array('value' => $value));

        if ($rc->isSubclassOf('widget_input'))
        {
            echo html_writer::tag('h2', 'Form');

            $mform = new test_form(null, array('klass' => $klass, 'config' => $config));
            if ($data = $mform->get_data())
            {
                print_object($data);
            }
            else
            {
                $mform->set_data(array('test_'.$klass => $value));
            }
            $mform->display();

            echo html_writer::tag('h2', 'Admin Setting');
            $setting = $klass::adminsetting('local_ui/test_'.$klass, 'name', 'description', $config, $value);
            echo $setting->output_html($value);
        }
    }
    catch (coding_exception $e)
    {
        echo html_writer::div($e->getMessage(), 'error');
    }
}
echo $OUTPUT->footer();
