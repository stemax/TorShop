<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TorShopModelCats extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`c`.`id`', '`c`.`title`', '`c`.`description`', '`c`.`published`'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search_cat', $this->getUserStateFromRequest('com_torshop.filter.search_cat', 'filter_search_cat'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('c.*');
        $query->from('`#__torshop_categories` AS `c`');

        $orderCol = $this->state->get('list.ordering', '`c`.`title`');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($orderCol . ' ' . $orderDirn);

        $search = $this->getState('filter.search_cat');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('`c`.`title` LIKE ' . $search);
        }

        return $query;
    }

}
