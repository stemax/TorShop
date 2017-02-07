<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class TorShopViewCat extends JViewLegacy
{

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
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
        JRequest::setVar('hidemainmenu', true);
        $user = JFactory::getUser();
        $isNew = $this->item->id == 0;
        JToolBarHelper::title($isNew ? JText::_('COM_TORSHOP_CREATING') : JText::_('COM_TORSHOP_EDIT'), 'categories');
        if ($isNew) {
            if ($this->user->authorise('core.create')) {
                JToolBarHelper::apply('cat.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('cat.save', 'JTOOLBAR_SAVE');
            }
        } else {
            if ($this->user->authorise('core.edit') or ($this->user->authorise('core.edit.own'))) {
                JToolBarHelper::apply('cat.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('cat.save', 'JTOOLBAR_SAVE');
            }
        }
        JToolBarHelper::cancel('cat.cancel', 'JTOOLBAR_CANCEL');
    }

    protected function setDocument()
    {
        $isNew = $this->item->id == 0;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_TORSHOP_CREATING') : JText::_('COM_TORSHOP_EDIT'));
    }

}
