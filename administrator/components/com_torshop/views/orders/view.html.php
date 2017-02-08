<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class TorshopViewOrders extends JViewLegacy
{

    function display($tpl = null)
    {
        $this->orders = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->user = JFactory::getUser();
        $this->sidebar = TorShopHelper::addSubmenu('orders');
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
        JToolBarHelper::title(JText::_('COM_TORSHOP_ORDERS'), 'orders');
        if ($this->user->authorise('core.create')) {
            JToolBarHelper::addNew('order.add', 'JTOOLBAR_NEW');
        }
        if ($this->user->authorise('core.edit') or $this->user->authorise('core.edit.own')) {
            JToolBarHelper::editList('cat.edit', 'JTOOLBAR_EDIT');
        }
        JToolBarHelper::divider();
        JToolBarHelper::publish('orders.publish', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::unpublish('orders.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolBarHelper::divider();

        if ($this->user->authorise('core.delete')) {
            JToolBarHelper::deleteList('', 'orders.delete', 'JTOOLBAR_DELETE');
        }
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_TORSHOP_ORDERS'));
    }

}
