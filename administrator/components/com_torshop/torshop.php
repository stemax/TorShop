<?php
defined('_JEXEC') or die;
if (!JFactory::getUser()->authorise("core.manage", "com_torshop")) {
    return JError::raiseWarning(404, JText::_("JERROR_ALERTNOAUTHOR"));
}

JLoader::register('TorShopHelper', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'helper.php');

jimport('joomla.application.component.controller');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_torshop/assets/css/torshop.css');
$controller = JControllerLegacy::getInstance('TorShop');

$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
