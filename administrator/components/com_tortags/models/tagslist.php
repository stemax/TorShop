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

class TorTagsModelTagslist extends JModelAdmin
{

    public function getTable($type = 'Tagslists', $prefix = 'TorTagsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_tortags.tagslist', 'component', array('control' => 'jform', 'load_data' => false));
        $item = $this->getItem();
        $form->bind($item);

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getItem($pk = null)
    {
        if (!isset($this->item)) {
            $pk = (!empty($pk)) ? $pk : (int)$this->getState($this->getName() . '.id');
            $table = $this->getTable();

            if ($pk > 0) {
                $return = $table->load($pk);

                if ($return === false && $table->getError()) {
                    $this->setError($table->getError());
                    return false;
                }
            }

            $properties = $table->getProperties(1);
            $this->item = new JObject($properties);
        }

        return $this->item;
    }

    public function getTags()
    {
        $tags = null;
        if (!isset($this->item)) {
            $this->item = $this->getItem();
        }
        if ($this->item->id) {
            $db = $this->_db;
            $query = $db->getQuery(true);

            $query->select('m.*, `t`.`title` AS `tag`');
            $query->from('`#__tortags` AS `m`');
            $query->leftJoin('`#__tortags_tags` AS `t` ON t.id=m.tid');
            if ($this->item->title_field && $this->item->table) {
                $query->select('d.`' . $this->item->title_field . '` AS `artitle`');
                $query->leftJoin('`' . $this->item->table . '` AS `d` ON d.`' . $this->item->id_field . '`=m.item_id');
            }
            $query->where('m.oid=' . $this->item->id);
            $query->order('`t`.`title` ASC');
            $db->setQuery($query);
            $tags = $db->loadObjectList();
        }
        return $tags;
    }

}
