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

class TorTagsModelCats extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`c`.`id`', '`c`.`catname`', 'tags_count', '`c`.`published`'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search_cat', $this->getUserStateFromRequest('com_tortags.filter.search_cat', 'filter_search_cat'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('c.*');
        $query->select('(SELECT COUNT(`t`.`tid`) FROM `#__tortags_cat_xref` AS `t` WHERE `t`.`cid`=`c`.`id`) AS `tags_count`');
        $query->from('`#__tortags_categories` AS `c`');

        if (TT_JVER != 3) {
            $orderCol = $db->escape($this->getState('list.ordering', '`c`.`catname`'));
            $orderDirn = $db->escape($this->getState('list.direction', 'ASC'));
        } else {
            $orderCol = $this->state->get('list.ordering', '`c`.`catname`');
            $orderDirn = $this->state->get('list.direction', 'ASC');
        }
        $query->order($orderCol . ' ' . $orderDirn);

        $search = $this->getState('filter.search_cat');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('`c`.`catname` LIKE ' . $search);
        }

        return $query;
    }

}
