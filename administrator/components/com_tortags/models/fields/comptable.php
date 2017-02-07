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

class JFormFieldComptable extends JFormField
{
    function getInput()
    {
        $input = '';

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $db->setQuery('SHOW TABLES');
        $tables = $db->loadColumn();

        $arr = null;
        $arr[''] = JText::_('COM_TORTAGS_NO_SELECTED');
        if (sizeof($tables)) {
            foreach ($tables as $table) {
                $tname = str_replace($db->getprefix(), '#__', $table);
                $arr[$tname] = $table;
            }
        }
        $js = 'onchange="javascript:getTables(this.value);"';
        $input = JHTML::_('select.genericlist', $arr, $this->name, $js . ' class="required"', 'value', 'text', $this->value);
        $input .= '</li><li id="tt_tables">&nbsp;';
        return $input;
    }
}
