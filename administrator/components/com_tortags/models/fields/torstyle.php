<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldTorstyle extends JFormField
{
    function getInput()
    {
        $input = '';

        $files = JFolder::files(JPATH_SITE . DS . 'components' . DS . 'com_tortags' . DS . 'assets' . DS . 'css', '.css');
        if (sizeof($files)) {
            foreach ($files as $file) {
                $arr[$file] = $file;
            }
        }
        $input = JHTML::_('select.genericlist', $arr, $this->name, 'id="jform_style"', 'value', 'text', $this->value);
        return $input;
    }
}
