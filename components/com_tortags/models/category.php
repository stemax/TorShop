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

class TorTagsModelCategory extends JModelList
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $settings = JComponentHelper::getParams('com_tortags');
        $db = $this->_db;
        $query = $db->getQuery(true);

        $catid = JRequest::getInt('id');

        $l = JRequest::getVar('letter');
        if ($l) {
            if ($l != 'cifr' && $l != 'all') {
                $l = substr(trim(strtolower($l)), 0, 1);
                if ($l) {
                    $query->where('`c`.`catname` LIKE "' . $l . '%"');
                }
            } else {
                if ($l == 'cifr') {
                    $query->where("`c`.`catname` REGEXP '[0-9]'");
                }
            }
        }

        if ($catid)
            $query->where('`c`.`id`=' . $catid);

        $query->select('`c`.*');
        $query->from('`#__tortags_categories` AS `c`');
        $query->where('`c`.`published`=1');
        $query->order('`c`.`catname` ASC');
        return $query;
    }

}
