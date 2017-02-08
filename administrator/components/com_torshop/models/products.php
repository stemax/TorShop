<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TorShopModelProducts extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`p`.`id`', '`p`.`title`', '`p`.`description`', '`p`.`published`'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search_product', $this->getUserStateFromRequest('com_torshop.filter.search_product', 'filter_search_product'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('p.*');
        $query->from('`#__torshop_products` AS `p`');

        $orderCol = $this->state->get('list.ordering', '`p`.`title`');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($orderCol . ' ' . $orderDirn);

        $search = $this->getState('filter.search_product');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('`p`.`title` LIKE ' . $search);
        }

        return $query;
    }

}
