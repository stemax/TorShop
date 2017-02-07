<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class TorTagsViewComponent extends JView
{

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->tags = $this->get('Tags');
        $this->user = JFactory::getUser();

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        JRequest::setVar('hidemainmenu', true);
        $user = JFactory::getUser();
        $isNew = $this->item->id == 0;
        JToolBarHelper::title($isNew ? JText::_('COM_TORTAGS_CREATING') : JText::_('COM_TORTAGS_EDIT'), 'components');
        if ($isNew) {
            if ($this->user->authorise('core.create')) {
                JToolBarHelper::apply('component.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('component.save', 'JTOOLBAR_SAVE');
            }
        } else {
            if ($this->user->authorise('core.edit') or ($this->user->authorise('core.edit.own'))) {
                JToolBarHelper::apply('component.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('component.save', 'JTOOLBAR_SAVE');
            }
        }
        JToolBarHelper::cancel('component.cancel', 'JTOOLBAR_CANCEL');
    }

    protected function setDocument()
    {
        $isNew = $this->item->id == 0;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_TORTAGS_CREATING') : JText::_('COM_TORTAGS_EDIT'));
    }

    public function getItemsByTable($item = null)
    {
        if (!$item)
            return null;

        $table = (isset($item->table) ? $item->table : '');
        $fieldid = (isset($item->id_field) ? $item->id_field : 'id');;
        $fieldtitle = (isset($item->title_field) ? $item->title_field : '');
        $fielddescr = (isset($item->description_field) ? $item->description_field : '');
        $fieldcreated = (isset($item->created_field) ? $item->created_field : '');
        $fieldstate = (isset($item->state_field) ? $item->state_field : '');
        $fieldvalue = (isset($item->state_status) ? $item->state_status : '');
        $fieldadminkey = (isset($item->be_key) ? $item->be_key : '');
        $fieldfrontkey = (isset($item->fe_key) ? $item->fe_key : '');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $db->setQuery('SHOW COLUMNS FROM ' . $table);
        $fields = $db->loadColumn();
        $arr = null;
        $arr[''] = '- No selected -';
        if (sizeof($fields)) {
            foreach ($fields as $field) {
                $arr[$field] = $field;
            }
        }

        $requiredS = '<span class="star">&nbsp;*</span>';

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FIELD_ID', 'COM_TORTAGS_COMPONENT_FIELD_ID_DESC');
        $input = '<ul><li><label for="jform_id_field">' . $caption . $requiredS . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[id_field]', 'id="jform_id_field"', 'value', 'text', $fieldid);

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FIELD_TITLE', 'COM_TORTAGS_COMPONENT_FIELD_TITLE_DESC');
        $input .= '</li><li><label for="jform_title_field">' . $caption . $requiredS . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[title_field]', 'id="jform_title_field"', 'value', 'text', $fieldtitle);

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FIELD_DESCRIPTION', 'COM_TORTAGS_COMPONENT_FIELD_DESCRIPTION_DESC');
        $input .= '</li><li id="edit_descr"><label for="jform_description_field">' . $caption . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[description_field]', 'id="jform_description_field"', 'value', 'text', $fielddescr);

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FIELD_CREATED', 'COM_TORTAGS_COMPONENT_FIELD_CREATED_DESC');
        $input .= '</li><li id="edit_cr"><label for="jform_created_field">' . $caption . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[created_field]', 'id="jform_created_field"', 'value', 'text', $fieldcreated);

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_STATE_FIELD', 'COM_TORTAGS_COMPONENT_STATE_FIELD_DESC');
        $input .= '</li><li><label for="jform_state_field">' . $caption . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[state_field]', 'id="jform_state_field"', 'value', 'text', $fieldstate);

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_STATE_STATUS', 'COM_TORTAGS_COMPONENT_STATE_STATUS_DESC');
        $input .= '</li><li><label for="jform_state_status">' . $caption . '</label>';
        $input .= '<input type="text" size="3" class="inputbox" value="' . $fieldvalue . '" id="jform_state_status" name="jform[state_status]" />';

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_ADMIN_KEY', 'COM_TORTAGS_COMPONENT_ADMIN_KEY_DESC');
        $input .= '</li><li><label for="jform_be_key">' . $caption . '</label>';
        $input .= '<input type="text" size="7" class="inputbox" value="' . $fieldadminkey . '" id="jform_be_key" name="jform[be_key]" />';

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FRONT_KEY', 'COM_TORTAGS_COMPONENT_FRONT_KEY_DESC');
        $input .= '</li><li><label for="jform_fe_key">' . $caption . '</label>';
        $input .= '<input type="text" size="7" class="inputbox" value="' . $fieldfrontkey . '" id="jform_fe_key" name="jform[fe_key]" /></ul>';

        return $input;
    }

}
