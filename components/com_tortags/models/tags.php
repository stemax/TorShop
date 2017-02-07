<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TorTagsModelTags extends JModelList
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search_tag', $this->getUserStateFromRequest('com_tortags.filter.search_tag', 'filter_search_tag'));
        parent::populateState();
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('t.*');
        $query->select('(SELECT COUNT(`g`.`oid`) FROM `#__tortags` AS `g` WHERE `g`.`tid`=`t`.`id`) AS `comp_count`');
        $query->from('`#__tortags_tags` AS `t`');

        $search = $this->getState('filter.search_tag');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
            $query->where('`t`.`title` LIKE ' . $search);
        }
        $query->order('`t`.`title` ASC');

        return $query;
    }

    public function getSelTags()
    {
        $elid = JRequest::getInt('eid');
        $oid = JRequest::getInt('oid');
        $mids = null;
        if ($elid) {
            $db = $this->_db;
            $query = $db->getQuery(true);

            $query->select('`m`.`tid`');
            $query->from('`#__tortags` AS `m`');
            $query->where('`m`.`oid`=' . $oid);
            $query->where('`m`.`item_id`=' . $elid);
            $db->setQuery($query);
            $mids = $db->loadColumn();
        }
        return $mids;
    }

}
