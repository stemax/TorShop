<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class TorShopControllerProducts extends JControllerAdmin
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getModel($name = 'Product', $prefix = 'TorShopModel')
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

}
