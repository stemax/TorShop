<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class TorShopModelProduct extends JModelAdmin
{

    public function getTable($type = 'Products', $prefix = 'TorShopTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_torshop.product', 'product', array('control' => 'jform', 'load_data' => false));
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

}
