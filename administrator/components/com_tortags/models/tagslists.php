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

class TorTagsModelTagslists extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`m`.`id`', '`t`.`title`', 'artitle', '`m`.`item_id`'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search_link', $this->getUserStateFromRequest('com_tortags.filter.search_link', 'filter_search_link'));
        $this->setState('filter.cid', $this->getUserStateFromRequest('com_tortags.filter.cid', 'filter_cid'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        if (TT_JVER != 3) {
            $cid = $this->getState('filter.cid');
        } else {
            $cid = $this->state->get('filter.cid');
        }

        if ($cid) {
            $comp = $this->getInfoComponent($cid);
        }
        $query->select('m.*, `t`.`title` AS `tag`');
        $query->from('`#__tortags` AS `m`');
        $query->leftjoin('`#__tortags_tags` AS `t` ON t.id=m.tid');

        if (isset($comp->title_field) && isset($comp->table)) {
            $query->select('d.`' . $comp->title_field . '` AS `artitle`');
            $query->leftjoin('`' . $comp->table . '` AS `d` ON d.`' . $comp->id_field . '`=m.item_id');
        }
        if (!isset($comp->id)) {
            $query->where('m.oid<0');
        } else
            $query->where('m.oid=' . $comp->id);
        if (TT_JVER != 3) {
            $orderCol = $db->escape($this->getState('list.ordering', '`t`.`title`'));
            $orderDirn = $db->escape($this->getState('list.direction', 'ASC'));
        } else {
            $orderCol = $this->state->get('list.ordering', '`t`.`title`');
            $orderDirn = $this->state->get('list.direction', 'ASC');
        }
        $query->order($orderCol . ' ' . $orderDirn);

        $search = $this->getState('filter.search_link');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('(`d`.`' . $comp->title_field . '` LIKE ' . $search . ' OR `t`.`title`LIKE ' . $search . ' )');
        }
        return $query;
    }

    protected function getInfoComponent($cid = 0)
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('`#__tortags_components`');
        $query->where('`id`=' . (int)$cid);
        $db->setQuery($query);
        return $db->loadObject();
    }

    public function getComponents()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('`id` AS value, `component` AS text');
        $query->from('`#__tortags_components`');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

}
