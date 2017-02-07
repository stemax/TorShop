<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class torshopController extends JControllerLegacy
{
    public function __construct($config = array())
    {
        return parent::__construct($config);
    }

    function display($cachable = false, $urlparams = array())
    {
        $viewName = JFactory::getApplication()->input->get('view', 'torshop');
        $this->default_view = $viewName;
        parent::display($cachable, $urlparams);
    }
}