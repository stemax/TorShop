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

class TorTagsViewTag extends JView
{

    function display($tpl = null)
    {
        $this->results = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->tag = $this->get('Tag');
        $this->description = $this->get('TagDescription');
        $this->user = JFactory::getUser();
        $this->tag_enc = $this->tag;
        $this->tag = TTHelper::replaceSpecSymbInTag($this->tag, 'decode');

        $app = JFactory::getApplication();
        $document = JFactory::getDocument();
        $settings = JComponentHelper::getParams('com_tortags');

        $pathway = $app->getPathway();
        $pathway->addItem($this->tag, JRoute::_('index.php?option=com_tortags&view=tag&tag=' . $this->tag));

        //Set meta
        $canonical_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . JRoute::_('index.php?option=com_tortags&view=tag&tag=' . $this->tag);
        $document->addHeadLink($canonical_url, 'canonical', 'rel');

        $meta_order = $settings->get('meta_order', 0);
        $comma = '';
        $menu = $app->getMenu();
        $m_el = $menu->getActive();

        if (is_object($m_el)) {
            $params = $m_el->params;
            $description = $params->get('menu-meta_description', '');
            $keywords = $params->get('menu-meta_keywords', '');
            if (!empty($description)) {
                $document->setMetadata('description', $description);
                $comma = ', ';
            }
            if (!empty($keywords)) {
                $document->setMetadata('keywords', $keywords);
                $comma = ', ';
            }
            if (empty($keywords) && empty($description) && $document->getMetaData('description')) {
                $comma = ', ';
            }
        }
        //

        $meta_keys_old = $document->getMetaData('keywords');
        $meta_desc_old = $document->getMetaData('description');
        $old_title = $document->getTitle();

        if (!$meta_order) {
            $document->setTitle($old_title . ' - ' . $this->tag);
            $document->setMetadata('keywords', $meta_keys_old . $comma . $this->tag);
            $document->setMetadata('description', $meta_desc_old . $comma . $this->tag);
        } else {
            $document->setTitle($this->tag . ' - ' . $old_title);
            $document->setMetadata('keywords', $this->tag . $comma . $meta_keys_old);
            $document->setMetadata('description', $this->tag . $comma . $meta_desc_old);
        }
        $style = $settings->get('style', 'red_tags.css');
        $document->addStylesheet(JURI::root() . 'components/com_tortags/assets/css/' . $style);

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        parent::display($tpl);
    }

}
