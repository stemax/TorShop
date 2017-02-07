<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class TorTagsModelSettings extends JModelAdmin
{

    public function getTable($type = 'settings', $prefix = 'TortagsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function allowEdit($data = array(), $key = 'id')
    {
        return JFactory::getUser()->authorise('core.admin', 'com_tortags');
    }

    protected function loadFormData()
    {
        $data = $this->getItem();
        return $data;
    }

    public function getItem($pk = null)
    {
        if (!isset($this->item)) {
            $db = JFactory::getDBO();
            $db->setQuery("SELECT `extension_id`, `params` FROM #__extensions WHERE name='com_tortags' AND element='com_tortags'");
            $config = $db->loadObject();

            $configString = $config->params;
            $cfg = json_decode($configString);
            $item = new stdClass();
            if (sizeof($cfg))
                foreach ($cfg as $key => $val) {
                    $item->$key = $val;
                }
            $item->id = $config->extension_id;
        }
        return $item;
    }

    public function getUnatachTags()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('COUNT(t.id) ');
        $query->from('`#__tortags_tags` AS `t`');
        $query->where('(SELECT COUNT(`g`.`oid`) FROM `#__tortags` AS `g` WHERE `g`.`tid`=`t`.`id`)=0');
        $db->setQuery($query);
        return (int)$db->loadResult();
    }

    public function getEmptyAliases()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('COUNT(t.id) ');
        $query->from('`#__tortags_tags` AS `t`');
        $query->where('`t`.`alias`=""');
        $db->setQuery($query);
        return (int)$db->loadResult();
    }

    public function getForm($data = array(), $loadData = true)
    {
        $app = JFactory::getApplication();

        $form = $this->loadForm('com_tortags.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

}