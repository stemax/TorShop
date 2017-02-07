<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
class torshopViewtorshop extends JViewLegacy
{
function display($tpl = null) 
{
  JToolbarHelper::title(JText::_("COM_TORSHOP"), "info");
  parent::display($tpl);
}
}