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

//require_once(JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table'.DS.'extension.php');

class TortagsTableSettings extends JTable
{

    function __construct(&$db)
    {
        parent::__construct('#__extensions', 'extension_id', $db);
        //$this->_trackAssets = true;
    }

    public function bind($array, $ignore = '')
    {
        if (isset($array['rules']) && is_array($array['rules'])) {
            $rules = new JRules($array['rules']);

            $asset = JTable::getInstance('Asset');
            $asset->loadByName('com_tortags');

            if ($asset->id) {

                $db = $this->getDBO();
                $query = $db->getQuery(true);
                $query->update('`#__assets`');
                $query->set('`rules`=' . $db->quote($rules));
                $query->where('`id`=' . $asset->id);
                $db->setQuery($query);
                $db->query();
            }
        }
        return parent::bind($array, $ignore);
    }


    public function store($updateNulls = false)
    {
        $options = array();

        $jform = JRequest::getVar('jform', array());
        if (sizeof($jform)) {
            foreach ($jform as $key => $jv) {
                if ($key != 'rules') {
                    $options[$key] = $jv;
                }
            }
        }

        $this->params = json_encode($options);
        $this->extension_id = JRequest::getInt('id', 0);

        return parent::store($updateNulls);
    }
    /*
        protected function _getAssetName() {
            return 'com_tortags';
        }

        protected function _getAssetTitle() {
            return 'tortags';
        }
      */

    //protected function _getAssetParentId()
    //{
    //$asset = JTable::getInstance('Asset');
    //$asset->loadByName('com_tortags');
    //return 1;
    //}
}