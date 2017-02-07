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

class TorTagsModelTag extends JModelList
{

    private $items = null;
    private $total = null;
    private $tag = null;
    public $settings = null;

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getTag()
    {
        if (!$this->tag) {
            $tag = JRequest::getVar('tag');
            $tag = trim(htmlspecialchars($tag));
            $tag = str_replace(':', '-', $tag);
            $this->tag = $tag;
        }
        return $this->tag;
    }

    public function getTagDescription()
    {
        $descr = '';
        if (!$this->tag) {
            $this->tag = $this->getTag();
        }
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('`description`');
        $query->from('`#__tortags_tags`');
        $query->where('`title`=' . $db->quote($this->tag));
        $db->setQuery($query);
        $descr = $db->loadResult();
        return $descr;
    }

    public function getTotal()
    {
        $tag = $this->getTag();
        $this->total = count(TTHelper::getTagResults($tag));
        return $this->total;
    }

    public function getStart($total = 0)
    {
        $store = $this->getStoreId('getstart');

        // Try to load the data from internal storage.
        if (!empty($this->cache[$store])) {
            return $this->cache[$store];
        }

        $settings = JComponentHelper::getParams('com_tortags');
        $limitstart = JRequest::getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
        $start = $this->getState('list.start');
        $limit = $this->getState('list.limit');
        $limit = $settings->get('result_limit', $limit);

        if ($start > $total - $limit) {
            $start = max(0, (int)(ceil($total / $limit) - 1) * $limit);
        }

        // Add the total to the internal cache.
        $this->cache[$store] = $start;

        return $this->cache[$store];
    }

    public function getItems()
    {
        // Get a storage key.
        $store = $this->getStoreId();

        // Try to load the data from internal storage.
        if (!empty($this->cache[$store])) {
            return $this->cache[$store];
        }

        $this->items = $items = null;

        $this->settings = $settings = JComponentHelper::getParams('com_tortags');

        // Load the list items.
        $tag = $this->getTag();
        $results = TTHelper::getTagResults($tag);
        $start = $this->getStart(count($results));
        $results = TTHelper::getPrepareTagResults($results, $tag, $start);
        $this->items = $results;

        $this->cache[$store] = $this->items;

        return $this->cache[$store];
    }

    public function getPagination()
    {
        // Get a storage key.
        $store = $this->getStoreId('getPagination');

        // Try to load the data from internal storage.
        if (!empty($this->cache[$store])) {
            return $this->cache[$store];
        }
        // Create the pagination object.
        jimport('joomla.html.pagination');
        $settings = JComponentHelper::getParams('com_tortags');

        $limit = $this->getState('list.limit');
        $limit = (int)$settings->get('result_limit', $limit);
        //$limit = (int) $this->getState('list.limit') - (int) $this->getState('list.links');
        $limit = $limit - (int)$this->getState('list.links');
        $page = new JPagination($this->getTotal(), $this->getStart($this->total), $limit);

        // Add the object to the internal cache.
        $this->cache[$store] = $page;

        return $this->cache[$store];
    }

}
