<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class TorTagsTableComponents extends JTable
{

    function __construct(&$db)
    {
        parent::__construct('#__tortags_components', 'id', $db);
    }

    public function check()
    {
        $db = $this->getDBO();

        $cur_id = 0;
        if (isset($this->id))
            $cur_id = $this->id;

        //Title check

        $query = $db->getQuery(true);
        $query->select('`id`');
        $query->from('`#__tortags_components`');
        $query->where('`component`=' . $db->quote($this->component));
        if ($cur_id) {
            $query->where('`id`<>' . (int)$cur_id);
        }
        $db->setQuery($query);
        $isset = $db->loadResult();
        if ($isset) {
            $this->setError(JText::_('COM_TORTAGS_WARNING_DUBLICATE_TITLE'));
            return false;
        }
        return true;
    }

    public function delete($pk = null)
    {
        $db = $this->getDBO();

        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__tortags');
        $query->where('oid=' . $pk);

        $db->setQuery($query);
        $ids = $db->loadColumn();

        if ($ids) {
            $elem = JTable::getInstance('Elements', 'TorTagsTable', array());
            foreach ($ids as $id) {
                $elem->delete($id);
            }
        }

        return parent::delete($pk);
    }

}
