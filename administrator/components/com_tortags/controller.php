<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class TorTagsController extends JController
{

    function display($cachable = false, $urlparams = false)
    {
        $viewName = JRequest::getVar('view', 'about');
        $this->default_view = $viewName;

        TorTagsHelper::addSubmenu($viewName);

        parent::display($cachable, $urlparams);
    }

    public function updatetags()
    {
        header('Content-Type: text/html; charset=UTF-8', true);
        JHTML::_('behavior.tooltip');
        $cid = JRequest::getInt('id', null);
        $option = strip_tags(trim(JRequest::getVar('comp', null)));

        $img_del = JURI::root() . 'components/com_tortags/assets/images/delete.png';

        $tags = $this->getTags($cid, $option);
        if (sizeof($tags)) {
            foreach ($tags as $tag) {
                $return = '';
                $title = JHTML::tooltip($tag->description, $tag->title, '', $tag->title);
                $return .= '<div id="tagid_' . $tag->id . '" class="tt_button"><div class="tt_end"><span class="tt_img">&nbsp;</span>';
                $return .= '<a class="tt_deltag" href="javascript:void(0);" onclick="javascript:delTag(' . $tag->id . ');">';
                $return .= '<img src="' . $img_del . '"/></a>';
                $return .= '<span class="tttag">' . $title . '</span>' .
                    '<span class="tt_img_end">&nbsp;</span>' .
                    '</div>' .
                    '</div>';
                echo $return;
            }
        }
        die;
    }

    protected function getTags($id, $option)
    {
        $db = JFactory::getDBO();

        $oid = $this->getOidByOption($option);

        $query = $db->getQuery(true);
        $query->select('`t`.*');
        $query->from('`#__tortags_tags` AS `t`');
        $query->join('INNER', '`#__tortags` AS `m` ON `m`.`tid`=`t`.`id`');
        $query->where('`m`.`item_id`=' . (int)$id);
        $query->where('`m`.`oid`=' . (int)$oid);
        $query->order('`t`.`title` ASC');
        $db->setQuery($query);
        $tags = $db->loadObjectList();
        return $tags;
    }

    private function getOidByOption($option)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('`id`');
        $query->from('`#__tortags_components`');
        $query->where('`component`=' . $db->quote($option));
        $db->setQuery($query);
        $oid = $db->loadResult();
        return (int)$oid;
    }

    public function addseltags()
    {
        $user = JFactory::getUser();
        if ($user->authorise('core.create', 'com_tortags')) {
            header('Content-Type: text/html; charset=UTF-8', true);
            JHtml::_('behavior.tooltip');

            $cid = JRequest::getInt('id', null);
            $isnew = JRequest::getInt('isnew', null);
            $tids_from_rqst = strip_tags(trim(JRequest::getVar('tids', null)));
            $option = strip_tags(trim(JRequest::getVar('comp', null)));

            $tags = array();

            if ((!$cid && !$isnew) || (!$tids_from_rqst) || (!$option)) {
                echo -1;
                die();
            }

            $db = JFactory::getDBO();

            $tids = explode(',', $tids_from_rqst);

            if (sizeof($tids)) {
                $query = $db->getQuery(true);
                $query->select('`id`');
                $query->from('`#__tortags_components`');
                $query->where('`component`=' . $db->quote($option));
                $db->setQuery($query);
                $oid = $db->loadResult();

                if (!$isnew) {
                    foreach ($tids as $tid) {
                        $query = $db->getQuery(true);
                        $query->select('`id`');
                        $query->from('`#__tortags`');
                        $query->where('`tid`=' . (int)$tid);
                        $query->where('`oid`=' . (int)$oid);
                        $query->where('`item_id`=' . (int)$cid);
                        $db->setQuery($query);
                        $id = $db->loadResult();
                        if (!$id) {
                            $object = new stdclass();
                            $object->tid = $tid;
                            $object->oid = $oid;
                            $object->item_id = $cid;
                            if ($db->insertObject('#__tortags', $object)) {
                                $query = $db->getQuery(true);
                                $query->select('*');
                                $query->from('`#__tortags_tags`');
                                $query->where('`id`=' . (int)$tid);
                                $db->setQuery($query);
                                $tg = $db->loadObject();
                                $tags[$tid] = $tg;
                            }
                        }
                    }
                } else {
                    foreach ($tids as $tid) {
                        $query = $db->getQuery(true);
                        $query->select('*');
                        $query->from('`#__tortags_tags`');
                        $query->where('`id`=' . (int)$tid);
                        $db->setQuery($query);
                        $tg = $db->loadObject();
                        $tags[$tid] = $tg->title;
                    }
                }

                if ($isnew) {
                    if (sizeof($tags))
                        foreach ($tags as $tid => $tag) {
                            echo $tag . ', ';
                        }
                    die();
                }
                if (sizeof($tags)) {
                    $img_del = JURI::root() . 'components/com_tortags/assets/images/delete.png';
                    foreach ($tags as $tid => $tag) {
                        $title = JHTML::tooltip($tag->description, $tag->title, '', $tag->title);
                        echo '<div id="tagid_' . $tid . '" class="tt_button">' .
                            '<div class="tt_end">' .
                            '<a href="javascript:void(0);" onclick="javascript:delTag(' . $tid . ');">' .
                            '<img src="' . $img_del . '"/></a>' .
                            '<span style="font-weight: normal;">' . $title . '</span>' .
                            '</div>' .
                            '</div>';
                    }
                }
                return;
            }
        }
        return -1;
    }

    public function addattach()
    {
        $user = JFactory::getUser();
        if ($user->authorise('core.create', 'com_tortags')) {
            $otid = JRequest::getInt('tid', null);
            $tag = strip_tags(trim(JRequest::getVar('tag', null)));

            if ((!$otid) || (!$tag)) {
                echo -1;
                die();
            }

            $db = JFactory::getDBO();

            $query = $db->getQuery(true);
            $query->select('`id`,`language`');
            $query->from('`#__tortags_tags`');
            $query->where('`title`=' . $db->quote($tag));
            $db->setQuery($query);
            $tag_info = $db->loadObject();
            if (isset($tag_info->id))
                $tid = (int)$tag_info->id;
            else
                $tid = 0;
            if (isset($tag_info->language))
                $la = $tag_info->language;
            else
                $la = '*';

            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('`#__tortags`');
            $query->where('`tid`=' . $otid);
            $db->setQuery($query);
            $cons = $db->loadObjectList();

            if (!$tid) {
                $object = new stdclass();
                $object->title = $tag;
                $object->alias = TorTagsHelper::stringMainURLSafe($tag);
                $object->published = 1;
                $object->language = $la;
                $db->insertObject('#__tortags_tags', $object);

                $tid = (int)$db->insertid();
            }
            //else {echo -2;die;} {
            if (sizeof($cons))
                foreach ($cons as $con) {
                    $query = $db->getQuery(true);
                    $query->select('`id`');
                    $query->from('`#__tortags`');
                    $query->where('`tid`=' . $tid);
                    $query->where('`oid`=' . $con->oid);
                    $query->where('`item_id`=' . $con->item_id);
                    $db->setQuery($query);
                    $id = $db->loadResult();

                    if (!$id) {
                        $object = new stdclass();
                        $object->tid = $tid;
                        $object->oid = $con->oid;
                        $object->item_id = $con->item_id;
                        $l = $db->insertObject('#__tortags', $object);
                    }
                }
            if (!isset($l)) {
                echo -3;
                die;
            }
        }
        echo $tid;
        jexit();
    }

    public function gettables()
    {
        header('Content-Type: text/html; charset=UTF-8', true);
        JHtml::_('behavior.tooltip');
        $table = JRequest::getVar('table');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $db->setQuery('SHOW COLUMNS FROM ' . $table);
        $fields = $db->loadColumn();
        $arr = null;
        $arr[''] = JText::_('COM_TORTAGS_NO_SELECTED');
        if (sizeof($fields)) {
            foreach ($fields as $field) {
                $arr[$field] = $field;
            }
        }

        $requiredS = '<span class="star">&nbsp;*</span>';

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FIELD_ID', 'COM_TORTAGS_COMPONENT_FIELD_ID_DESC');
        $input = '<ul><li><label for="jform_id_field">' . $caption . $requiredS . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[id_field]', 'id="jform_id_field"', 'value', 'text');

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FIELD_TITLE', 'COM_TORTAGS_COMPONENT_FIELD_TITLE_DESC');
        $input .= '</li><li><label for="jform_title_field">' . $caption . $requiredS . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[title_field]', 'id="jform_title_field"', 'value', 'text');

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FIELD_DESCRIPTION', 'COM_TORTAGS_COMPONENT_FIELD_DESCRIPTION_DESC');
        $input .= '</li><li><label for="jform_description_field">' . $caption . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[description_field]', 'id="jform_description_field"', 'value', 'text');

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FIELD_CREATED', 'COM_TORTAGS_COMPONENT_FIELD_CREATED_DESC');
        $input .= '</li><li><label for="jform_created_field">' . $caption . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[created_field]', 'id="jform_created_field"', 'value', 'text');

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_STATE_FIELD', 'COM_TORTAGS_COMPONENT_STATE_FIELD_DESC');
        $input .= '</li><li><label for="jform_state_field">' . $caption . '</label>';
        $input .= JHTML::_('select.genericlist', $arr, 'jform[state_field]', 'id="jform_state_field"', 'value', 'text');

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_STATE_STATUS', 'COM_TORTAGS_COMPONENT_STATE_STATUS_DESC');
        $input .= '</li><li><label for="jform_state_status">' . $caption . '</label>';
        $input .= '<input type="text" size="3" class="inputbox" value="" id="jform_state_status" name="jform[state_status]" />';

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_ADMIN_KEY', 'COM_TORTAGS_COMPONENT_ADMIN_KEY_DESC');
        $input .= '</li><li><label for="jform_be_key">' . $caption . '</label>';
        $input .= '<input type="text" size="7" class="inputbox" value="" id="jform_be_key" name="jform[be_key]" />';

        $caption = TorTagsHelper::getTTCaption('COM_TORTAGS_COMPONENT_FRONT_KEY', 'COM_TORTAGS_COMPONENT_FRONT_KEY_DESC');
        $input .= '</li><li><label for="jform_fe_key">' . $caption . '</label>';
        $input .= '<input type="text" size="7" class="inputbox" value="" id="jform_fe_key" name="jform[fe_key]" /></ul>';

        echo $input;
        exit();
    }

    protected function getTTCaption($title = '', $desc = '')
    {
        $title = JText::_($title);
        $desc = JText::_($desc);
        return JHTML::tooltip($desc, $title, 'tooltip.png', $title);
    }

    public function fixaliases()
    {
        $user = JFactory::getUser();
        if ($user->authorise('core.create', 'com_tortags')) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('`t`.*');
            $query->from('`#__tortags_tags` AS `t`');
            $query->where('`t`.`alias`=""');
            $db->setQuery($query);
            $tags = $db->loadObjectList();

            if (sizeof($tags)) {
                foreach ($tags as $t) {
                    $alias = TorTagsHelper::stringMainURLSafe($t->title);
                    $db->setQuery('UPDATE `#__tortags_tags` SET `alias`="' . $alias . '" WHERE `id`=' . (int)$t->id);
                    $db->query();
                }
            }
        }
        die();
    }

    public function delunattachtags()
    {
        $user = JFactory::getUser();
        if ($user->authorise('core.delete', 'com_tortags')) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('DISTINCT(t.id) ');
            $query->from('`#__tortags_tags` AS `t`');
            $query->where('(SELECT COUNT(`g`.`oid`) FROM `#__tortags` AS `g` WHERE `g`.`tid`=`t`.`id`)=0');
            $db->setQuery($query);
            $utags = $db->loadColumn();
            if (sizeof($utags)) {
                foreach ($utags as $tid) {
                    $db->setQuery('DELETE FROM `#__tortags_tags` WHERE `id`=' . (int)$tid);
                    $db->query();
                }
            }
        }
        die();
    }

    public function deletelink()
    {
        $id = JRequest::getInt('id');
        $user = JFactory::getUser();
        if ($user->authorise('core.delete', 'com_tortags')) {
            $db = JFactory::getDBO();
            $db->setQuery('DELETE FROM `#__tortags` WHERE `id`=' . (int)$id);
            $db->query();
        }
        echo '<td colspan="5" align="center"><small>' . JText::_('COM_TORTAGS_DELETED') . '</small></td>';
        exit();
    }

    public function history()
    {
        echo '<h2>' . JText::_('COM_TORTAGS_VERSION_HISTORY') . '</h2><br/>';
        jimport('joomla.filesystem.file');
        if (!JFile::exists(JPATH_COMPONENT_ADMINISTRATOR . '/changelog.txt')) {
            echo 'History file not found.';
        } else {
            echo '<textarea class="editor" rows="30" cols="50" style="width:100%">';
            echo JFile::read(JPATH_COMPONENT_ADMINISTRATOR . '/changelog.txt');
            echo '</textarea>';
        }
        exit();
    }

    private function getLanguageById($id = 0, $table = '#__content', $field = 'language')
    {
        $db = JFactory::getDBO();
        $cl_query = $db->getQuery(true);
        $cl_query->select('`' . $field . '`');
        $cl_query->from('`' . $table . '`');
        $cl_query->where('`id`=' . $id);
        $db->setQuery($cl_query);
        $cl_language = $db->loadResult();
        return $cl_language;
    }

    public function addtag()
    {
        $user = JFactory::getUser();
        if ($user->authorise('core.create', 'com_tortags')) {
            $cid = JRequest::getInt('id', null);
            $tag = strip_tags(trim(JRequest::getVar('tag', null)));
            $option = strip_tags(trim(JRequest::getVar('comp', null)));
            $settings = JComponentHelper::getParams('com_tortags');
            $ml = $settings->get('multilang_mode_enabled');

            if ((!$cid) || (!$tag) || (!$option)) {
                echo -1;
                die();
            }

            $db = JFactory::getDBO();

            $query = $db->getQuery(true);
            $query->select('`id`');
            $query->from('`#__tortags_tags`');
            $query->where('`title`=' . $db->quote($tag));
            $db->setQuery($query);
            $tid = $db->loadResult();

            $query = $db->getQuery(true);
            $query->select('`id`,`def_lang`');
            $query->from('`#__tortags_components`');
            $query->where('`component`=' . $db->quote($option));
            $db->setQuery($query);
            $compnt = $db->loadObject();
            $oid = $compnt->id;
            $dl = $compnt->def_lang ? $compnt->def_lang : '*';

            if (!$tid) {
                $object = new stdclass();
                $object->title = $tag;
                $object->language = $dl;
                $object->published = 1;
                $object->alias = TorTagsHelper::stringMainURLSafe($tag);
                if ($dl == '*' && !$ml) {
                    $object->language = JFactory::getLanguage()->getTag();
                } else if ($ml) {
                    if ($option == 'com_content') {
                        $cl_language = $this->getLanguageById($cid);
                        $object->language = $cl_language;
                    } else
                        $object->language = $dl;
                }
                $db->insertObject('#__tortags_tags', $object);
                $tid = (int)$db->insertid();
            } else {
                $query = $db->getQuery(true);
                $query->select('`id`');
                $query->from('`#__tortags`');
                $query->where('`tid`=' . $tid);
                $query->where('`oid`=' . $oid);
                $query->where('`item_id`=' . $cid);
                $db->setQuery($query);
                $id = $db->loadResult();
                if ($id) {
                    echo -2;
                    die();
                }
            }

            $object = new stdclass();
            $object->tid = $tid;
            $object->oid = $oid;
            $object->item_id = $cid;
            if ($db->insertObject('#__tortags', $object))
                echo (int)$tid;
            else {
                echo -3;
            }
        }
        die();
    }

    function deltag()
    {
        $user = JFactory::getUser();
        if ($user->authorise('core.delete', 'com_tortags')) {
            $tid = JRequest::getInt('tag_id', null);
            $cid = JRequest::getInt('id', null);
            $option = strip_tags(trim(JRequest::getVar('comp', null)));

            if ((!$cid) || (!$tid) || (!$option)) {
                echo -1;
                die();
            }

            $db = JFactory::getDBO();

            $query = $db->getQuery(true);
            $query->select('`id`');
            $query->from('`#__tortags_components`');
            $query->where('`component`=' . $db->quote($option));
            $db->setQuery($query);
            $oid = $db->loadResult();

            $query = $db->getQuery(true);
            $query->delete('#__tortags');
            $query->where('`tid`=' . $tid);
            $query->where('`oid`=' . $oid);
            $query->where('`item_id`=' . $cid);
            $db->setQuery($query);
            $db->query();
            echo 1;
        }
        die();
    }

}


