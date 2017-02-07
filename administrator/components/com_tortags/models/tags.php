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

class TorTagsModelTags extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`t`.`id`', '`t`.`title`', '`comp_count`', '`t`.`hits`', '`t`.`published`', '`t`.`language`'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search_tag', $this->getUserStateFromRequest('com_tortags.filter.search_tag', 'filter_search_tag'));

        $language = $this->getUserStateFromRequest('com_tortags.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);

        $cid = $this->getUserStateFromRequest('com_tortags.filter.cid', 'filter_cid', '');
        $this->setState('filter.filter_cid', $cid);

        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('t.*');
        $query->select('(SELECT COUNT(`g`.`oid`) FROM `#__tortags` AS `g` WHERE `g`.`tid`=`t`.`id`) AS `comp_count`');
        $query->from('`#__tortags_tags` AS `t`');

        $query->select('l.title AS language_title');
        $query->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = t.language');

        $cid = $this->getState('filter.filter_cid');
        if ($cid > 0) {
            $query->join('INNER', '`#__tortags_cat_xref` AS `h` ON `h`.`tid` = `t`.`id`');
            $query->join('INNER', '`#__tortags_categories` AS `c` ON `c`.`id` = `h`.`cid`');
            $query->where('`c`.`id` = ' . $db->quote($cid));
        }
        // Filter on the language.
        if ($language = $this->getState('filter.language')) {
            $query->where('t.language = ' . $db->quote($language));
        }

        if (TT_JVER != 3) {
            $orderCol = $db->escape($this->getState('list.ordering', '`t`.`title`'));
            $orderDirn = $db->escape($this->getState('list.direction', 'ASC'));
        } else {
            $orderCol = $this->state->get('list.ordering', '`t`.`title`');
            $orderDirn = $this->state->get('list.direction', 'ASC');
        }
        $query->order($orderCol . ' ' . $orderDirn);


        $search = $this->getState('filter.search_tag');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('`t`.`title` LIKE ' . $search);
        }
        return $query;
    }

    public function getSelTags()
    {
        $elid = JRequest::getInt('eid');
        $oid = JRequest::getInt('oid');
        $mids = null;
        if ($elid) {
            $db = $this->_db;
            $query = $db->getQuery(true);

            $query->select('`m`.`tid`');
            $query->from('`#__tortags` AS `m`');
            $query->where('`m`.`oid`=' . $oid);
            $query->where('`m`.`item_id`=' . $elid);
            $db->setQuery($query);
            $mids = $db->loadColumn();
        }
        return $mids;
    }

    public function getCatList()
    {

        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('`c`.`id` AS `key`,`c`.`catname` AS `value`');
        $query->from('`#__tortags_categories` AS `c`');
        $db->setQuery($query);
        $cats = $db->loadObjectList();
        return $cats;
    }

}
