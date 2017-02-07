<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TorTagsModelAbout extends JModelList
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('c.*');
        $query->from('`#__tortags_components` AS `c`');
        return $query;
    }

    public function getInfo()
    {
        $info = array();
        $info['tt_sys_plgn'] = $this->getExtension('tortags', 'system');
        $info['tt_cont_plgn'] = $this->getExtension('tortags', 'content');
        $info['tt_but_plgn'] = $this->getExtension('tortags', 'editors-xtd');
        $info['tt_search_plgn'] = $this->getExtension('tortags', 'search');
        $info['tt_finder_plgn'] = $this->getExtension('tortags', 'finder');
        $info['tt_module_cloud'] = $this->getExtension('mod_tortags_often_clouds');
        $info['tt_module_hits'] = $this->getExtension('mod_tortags_hits');
        $info['tt_module_elms'] = $this->getExtension('mod_tortags_elmsbytag');

        $info['tags_count'] = $this->getTagsCount();
        $info['tags_public'] = $this->getTagsPublic();
        $info['cats_count'] = $this->getCatsCount();
        $info['com_count'] = $this->getComCount();
        return $info;
    }

    protected function getComCount()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from('`#__tortags_components`');
        $db->setQuery($query);
        return (int)$db->loadResult();
    }

    protected function getCatsCount()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from('`#__tortags_categories`');
        $db->setQuery($query);
        return (int)$db->loadResult();
    }

    protected function getTagsCount()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from('`#__tortags_tags`');
        $db->setQuery($query);
        return (int)$db->loadResult();
    }

    protected function getTagsPublic()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from('`#__tortags_tags`');
        $query->where('`published`=1');
        $db->setQuery($query);
        return (int)$db->loadResult();
    }

    protected function getExtension($name = '', $folder = '')
    {
        if (!$name)
            return null;
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('e.*');
        $query->from('`#__extensions` AS `e`');
        $query->where('`e`.`element`=' . $db->quote($name));
        $query->where('`e`.`folder`=' . $db->quote($folder));
        $db->setQuery($query);
        return $db->loadObject();
    }

}