<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

if (!JFactory::getUser()->authorise('core.manage', 'com_tortags')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//For j3.0
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
$version = new JVersion;
$ver = $version->getShortVersion();
if (!defined('TT_JVER'))
    define('TT_JVER', (int)substr($ver, 0, strpos($ver, '.')));
if (TT_JVER == 3) {
    class JView extends JViewLegacy
    {
    }

    ;

    class JController extends JControllerLegacy
    {
    }

    ;
}

JLoader::register('TorTagsHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'helper.php');

jimport('joomla.application.component.controller');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_tortags/assets/css/tortags.css');
$controller = JController::getInstance('TorTags');
if (TT_JVER == 3) {
    $controller->execute(JFactory::getApplication()->input->get('task'));
} else {
    $controller->execute(JRequest::getVar('task'));
}
$controller->redirect();