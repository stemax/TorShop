<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TorShopModelOrders extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`o`.`id`', '`o`.`title`', '`o`.`description`', '`o`.`published`'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search_order', $this->getUserStateFromRequest('com_torshop.filter.search_order', 'filter_search_order'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('o.*');
        $query->from('`#__torshop_orders` AS `o`');

        $orderCol = $this->state->get('list.ordering', '`o`.`order_date`');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($orderCol . ' ' . $orderDirn);

        $search = $this->getState('filter.search_order');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('`o`.`name` LIKE ' . $search);
        }

        return $query;
    }

}
