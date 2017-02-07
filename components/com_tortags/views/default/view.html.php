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

class TorTagsViewDefault extends JView
{

    function display($tpl = null)
    {
        $this->tags = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->user = JFactory::getUser();

        $app = JFactory::getApplication();
        $settings = JComponentHelper::getParams('com_tortags');
        $document = JFactory::getDocument();
        //Set meta
        $menu = $app->getMenu();
        $m_el = $menu->getActive();
        if (is_object($m_el)) {
            $params = $m_el->params;
            $description = $params->get('menu-meta_description', '');
            $keywords = $params->get('menu-meta_keywords', '');
            if (!empty($description)) {
                $document->setMetadata('description', $description);
            }
            if (!empty($keywords)) {
                $document->setMetadata('keywords', $keywords);
            }
        }
        //
        $style = $settings->get('style', 'red_tags.css');
        JFactory::getDocument()->addStylesheet(JURI::root() . 'components/com_tortags/assets/css/' . $style);

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        parent::display($tpl);
    }

}
