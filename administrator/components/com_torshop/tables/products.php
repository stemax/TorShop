<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class TorShopTableProducts extends JTable
{

    function __construct(&$db)
    {
        parent::__construct('#__torshop_products', 'id', $db);
    }

}
