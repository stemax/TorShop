<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class TorShopTableCats extends JTable
{

    function __construct(&$db)
    {
        parent::__construct('#__torshop_categories', 'id', $db);
    }

}
