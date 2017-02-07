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

class TorTagsModelDefault extends JModelList
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState($ordering, $direction);
        //$this->setState('list.start', 0);
        $settings = JComponentHelper::getParams('com_tortags');
        $limit = $settings->get('alphalimit', 50);
        $this->state->set('list.limit', $limit);
        if ($this->context) {
            $app = JFactory::getApplication();
            $value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
            $limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
            $this->setState('list.start', $limitstart);
        }
    }

    protected function getListQuery()
    {
        $settings = JComponentHelper::getParams('com_tortags');
        $multilang_mode_enabled = $settings->get('multilang_mode_enabled');

        $db = $this->_db;
        $query = $db->getQuery(true);
        $l = JRequest::getVar('letter');
        if ($l) {
            if ($l != 'cifr' && $l != 'all') {
                //$l = substr(trim(strtolower($l)),0,1);
                if ($l) {
                    $query->where('`t`.`title` LIKE "' . $l . '%"');
                }
            } else {
                if ($l == 'cifr') {
                    $query->where("`t`.`title` REGEXP '[0-9]'");
                }
            }
        }
        if ($multilang_mode_enabled) {
            $query->where('`t`.`language` in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
        }

        $query->select('`t`.*');
        $query->from('`#__tortags_tags` AS `t`');
        $query->join('INNER', '`#__tortags` AS `m` ON `m`.`tid`=`t`.`id`');
        $query->group('`t`.`id`');
        $order = $settings->get('alltags_ordering', '');
        if ($order) {
            $query->order('`t`.`title` ' . strtoupper($order));
        }
        return $query;
    }

}
