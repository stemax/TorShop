<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TorTagsViewSettings extends JView
{

    protected $state;
    protected $item;
    protected $form;

    function display($tpl = null)
    {
        $submenu = 'settings';
        $this->settings = $settings = JComponentHelper::getParams('com_tortags');

        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $this->ut = $this->get('UnatachTags');
        $this->ea = $this->get('EmptyAliases');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolBar();
        $this->setDocument();
        parent::display($tpl);
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_TORTAGS_HEAD_SETTINGS'));
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        //JRequest::setVar('hidemainmenu', true);
        JToolBarHelper::title(JText::_('COM_TORTAGS_HEAD_SETTINGS'), 'settings');
        JToolBarHelper::apply('settings.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::cancel('settings.cancel');
    }

}
