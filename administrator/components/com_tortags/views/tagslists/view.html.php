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

class TorTagsViewTagslists extends JView
{

    function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->components = $this->get('Components');
        $this->user = JFactory::getUser();

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_TORTAGS_COMPONENTS_ASSIGNS'), 'components');
        if ($this->user->authorise('core.delete')) {
            JToolBarHelper::deleteList('', 'tagslists.delete', 'JTOOLBAR_DELETE');
        }
        JToolBarHelper::back('Back', JURI::root() . 'administrator/index.php?option=com_tortags&view=components');
        if ($this->user->authorise('core.admin')) {
            //JToolBarHelper::divider();
            //JToolBarHelper::preferences('com_tortags');
        }
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_TORTAGS_COMPONENTS_ASSIGNS'));
    }

}
