<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class TorShopTableOrders extends JTable
{

    function __construct(&$db)
    {
        parent::__construct('#__torshop_orders', 'id', $db);
    }

}
