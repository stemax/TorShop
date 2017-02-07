<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class TorTagsTableTags extends JTable
{

    function __construct(&$db)
    {
        parent::__construct('#__tortags_tags', 'id', $db);
    }

    public function check()
    {
        $db = $this->getDBO();

        $cur_id = 0;
        if (isset($this->id))
            $cur_id = $this->id;

        //Title check
        $query = $db->getQuery(true);
        $query->select('`id`');
        $query->from('`#__tortags_tags`');
        $query->where('`title`=' . $db->quote($this->title));
        if ($cur_id) {
            $query->where('`id`<>' . (int)$cur_id);
        }
        $db->setQuery($query);
        $isset = $db->loadResult();
        if ($isset) {
            $this->setError(JText::_('COM_TORTAGS_WARNING_DUBLICATE_TITLE'));
            return false;
        }

        //Aliases check

        $tortags_settings = &JComponentHelper::getParams('com_tortags');
        $symbol = $tortags_settings->get('alias_replace_symbol', '_');

        if (empty($this->alias)) {
            $this->alias = $this->title;
        }


        $this->alias = TorTagsHelper::stringMainURLSafe($this->alias);
        if (trim(str_replace($symbol, '', $this->alias)) == '') {
            $this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
        }

        $query = $db->getQuery(true);
        $query->select('`id`');
        $query->from('`#__tortags_tags`');
        $query->where('`alias`=' . $db->quote($this->alias));
        $query->where('`id`<>' . (int)$cur_id);
        $db->setQuery($query);
        $isset = $db->loadResult();
        if ($isset)
            $this->alias = $this->alias . '_2';

        return true;
    }

    public function store($updateNulls = false)
    {
        $r = parent::store($updateNulls);
        if ($r) {
            $tid = $this->id;
            $cats = JRequest::getVar('catid');
            $db = $this->getDBO();
            if (sizeof($cats)) {
                foreach ($cats as $cid) {
                    if ($cid == 0)
                        continue;
                    $query = $db->getQuery(true);
                    $query->select('`x`.`id`');
                    $query->from('`#__tortags_cat_xref` AS `x`');
                    $query->where('`x`.`tid`=' . $tid);
                    $query->where('`x`.`cid`=' . $cid);
                    $db->setQuery($query);
                    $isset = $db->loadResult();
                    if (!$isset) {
                        $query = $db->getQuery(true);
                        $query->insert('`#__tortags_cat_xref`');
                        $query->set('`tid`=' . $tid);
                        $query->set('`cid`=' . $cid);
                        $db->setQuery($query);
                        $db->query();
                    }
                }
                $query = $db->getQuery(true);
                $query->delete('`#__tortags_cat_xref`');
                $query->where('`cid` NOT IN (' . implode(',', $cats) . ')');
                $query->where('`tid`=' . $tid);
                $db->setQuery($query);
                $db->query();
            }
        }
        return $r;
    }

    public function delete($pk = null)
    {
        $db = $this->getDBO();

        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__tortags');
        $query->where('tid=' . $pk);

        $db->setQuery($query);
        $ids = $db->loadColumn();

        if ($ids) {
            $elem = JTable::getInstance('Elements', 'TorTagsTable', array());
            foreach ($ids as $id) {
                $elem->delete($id);
            }
        }

        return parent::delete($pk);
    }

}
