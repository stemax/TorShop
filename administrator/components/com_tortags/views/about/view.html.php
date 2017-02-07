<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class TorTagsViewAbout extends JView
{

    function display($tpl = null)
    {
        $this->info = $this->get('Info');
        $this->addToolBar();
        $this->setDocument();
        $this->user = JFactory::getUser();
        parent::display($tpl);
    }

    protected function addToolBar()
    {
        $user = JFactory::getUser();
        JToolBarHelper::title(JText::_('COM_TORTAGS_ABOUT'), 'about');
        if ($user->authorise('core.admin')) {
            //JToolBarHelper::preferences('com_tortags');
        }
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_TORTAGS_ABOUT'));
    }

}