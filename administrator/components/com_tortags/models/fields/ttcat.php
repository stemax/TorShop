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

class JFormFieldTtcat extends JFormField
{
    function getInput()
    {
        $input = '';
        $id = JRequest::getInt('id');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('`c`.`id` as `value`');
        $query->select('`c`.`catname` as `text`');
        $query->from('`#__tortags_categories` AS `c`');
        $query->where('`c`.`published`=1');
        $db->setQuery($query);
        $cats = $db->loadObjectList();

        $opt = new stdClass();
        $opt->value = 0;
        $opt->text = JText::_('COM_TORTAGS_NOSELECTED');
        $sel = array();
        $sel[] = $opt;
        if (sizeof($cats)) $sel = array_merge($sel, $cats);
        $selected = $this->getSelectedCats($id);
        if (!sizeof($selected)) $selected = 0;
        $input = JHTML::_('select.genericlist', $sel, 'catid[]', ' size="3" multiple="multiple"', 'value', 'text', $selected);
        return $input;
    }

    protected function getSelectedCats($tid = 0)
    {
        if (!$tid) return array();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('`x`.`cid`');
        $query->from('`#__tortags_cat_xref` AS `x`');
        $query->where('`x`.`tid`=' . $tid);
        $db->setQuery($query);
        $sel = $db->loadColumn();
        return $sel;
    }
}
