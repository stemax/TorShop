<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class TorshopViewCats extends JViewLegacy
{

    function display($tpl = null)
    {
        $this->cats = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->user = JFactory::getUser();
        $this->sidebar = TorShopHelper::addSubmenu('cats');
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
        JToolBarHelper::title(JText::_('COM_TORSHOP_CATS'), 'categories');
        if ($this->user->authorise('core.create')) {
            JToolBarHelper::addNew('cat.add', 'JTOOLBAR_NEW');
        }
        if ($this->user->authorise('core.edit') or $this->user->authorise('core.edit.own')) {
            JToolBarHelper::editList('cat.edit', 'JTOOLBAR_EDIT');
        }
        JToolBarHelper::divider();
        JToolBarHelper::publish('cats.publish', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::unpublish('cats.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolBarHelper::divider();

        if ($this->user->authorise('core.delete')) {
            JToolBarHelper::deleteList('', 'cats.delete', 'JTOOLBAR_DELETE');
        }
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_TORSHOP_CATS'));
    }

}
