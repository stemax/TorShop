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

class TorTagsModelImpexp extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`c`.`id`', '`c`.`component`', 'tags_count'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search', $this->getUserStateFromRequest('com_tortags.filter.search', 'filter_search'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('c.*');
        $query->select('(SELECT COUNT(`t`.`oid`) FROM `#__tortags` AS `t` WHERE `t`.`oid`=`c`.`id`) AS `tags_count`');
        $query->from('`#__tortags_components` AS `c`');
        $query->order($db->getEscaped($this->getState('list.ordering', '`c`.`component`')) . ' ' . $db->getEscaped($this->getState('list.direction', 'ASC')));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
            $query->where('`c`.`component` LIKE ' . $search);
        }
        return $query;
    }

}
