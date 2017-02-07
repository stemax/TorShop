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

class TorTagsViewTagslist extends JView
{

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->tags = $this->get('Tags');
        $this->user = &JFactory::getUser();

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
        JRequest::setVar('hidemainmenu', true);
        $user = JFactory::getUser();
        $isNew = $this->item->id == 0;
        JToolBarHelper::title($isNew ? JText::_('COM_TORTAGS_CREATING') : JText::_('COM_TORTAGS_EDIT'), 'components');
        if ($isNew) {
            if ($this->user->authorise('core.create')) {
                JToolBarHelper::apply('component.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('component.save', 'JTOOLBAR_SAVE');
            }
        } else {
            if ($this->user->authorise('core.edit') or ($this->user->authorise('core.edit.own'))) {
                JToolBarHelper::apply('component.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('component.save', 'JTOOLBAR_SAVE');
            }
        }
        JToolBarHelper::cancel('component.cancel', 'JTOOLBAR_CANCEL');
    }

    protected function setDocument()
    {
        $isNew = $this->item->id == 0;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_TORTAGS_CREATING') : JText::_('COM_TORTAGS_EDIT'));
    }

}
