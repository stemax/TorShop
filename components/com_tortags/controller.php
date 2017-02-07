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

    function display($cachable = false, $urlparams = array())
    {
        $settings = JComponentHelper::getParams('com_tortags');
        $deltaginurl = $settings->get('del_tag_in_url');
        $vdef = 'default';
        if ($deltaginurl)
            $vdef = 'tag';

        $viewName = JRequest::getCmd('view', $vdef);
        $this->default_view = $viewName;
        parent::display($cachable);
    }

    public function updatetags()
    {
        header('Content-Type: text/html; charset=UTF-8', true);
        JHTML::_('behavior.tooltip');
        $cid = JRequest::getInt('id', null);
        $option = strip_tags(trim(JRequest::getVar('comp', null)));

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('`id`');
        $query->from('`#__tortags_components`');
        $query->where('`component`=' . $db->quote($option));
        $db->setQuery($query);
        $oid = $db->loadResult();

        $img_del = JURI::root() . 'components/com_tortags/assets/images/delete.png';

        $tags = $this->getTags($cid, $option);
        if (sizeof($tags)) {
            foreach ($tags as $tag) {
                $return = '';
                $title = JHTML::tooltip($tag->description, $tag->title, '', $tag->title);
                $return .= '<div id="tagid_' . $tag->id . '" class="tt_button">
			 				<div class="tt_end"><span class="tt_img">&nbsp;</span>';
                $return .= '<a class="tt_deltag" href="javascript:void(0);" onclick="javascript:delTag(' . $tag->id . ');">';
                $return .= '<img src="' . $img_del . '"/></a>';
                $return .= '' .
                    '<span class="tttag">' . $title . '</span>' .
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
            JHtml::_('behavior.tooltip');

            $cid = JRequest::getInt('id', null);
            $isnew = JRequest::getInt('isnew', null);
            $tids = strip_tags(trim(JRequest::getVar('tids', null)));
            $option = strip_tags(trim(JRequest::getVar('comp', null)));

            $tags = array();

            if ((!$cid && !$isnew) || (!$tids) || (!$option)) {
                echo -1;
                die();
            }

            $db = JFactory::getDBO();

            $tids = explode(',', $tids);

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
                $return = '';
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

            if ((!$cid) || (!$tag) || (!$option)) {
                echo -1;
                die();
            }

            $db = JFactory::getDBO();

            $settings = JComponentHelper::getParams('com_tortags');
            $ml = $settings->get('multilang_mode_enabled');

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
                if ($dl == '*' && !$ml) {
                    $object->language = JFactory::getLanguage()->getTag();
                } else if ($ml) {
                    if ($option == 'com_content') {
                        $cl_language = $this->getLanguageById($cid);
                        $object->language = $cl_language;
                    } else
                        $object->language = $dl;
                }
                $object->published = 1;
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
        if ($user->authorise('core.create', 'com_tortags') && $user->authorise('core.delete', 'com_tortags')) {
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

    function prevcss()
    {
        $file = JRequest::getWord('f');
        $document = &JFactory::getDocument();
        $document->addStylesheet(JURI::root() . 'components/com_tortags/assets/css/' . $file . '.css');
        ?>
        <style type="text/css">
            #main {
                min-height: 10px !important;
            }
        </style>
        <div class="tt-tags">
            <?php
            echo TTHelper::showTorTagsTag('Demo');
            echo TTHelper::showTorTagsTag('TorTags ');
            echo TTHelper::showTorTagsTag('3.0');
            echo TTHelper::showTorTagsTag('Joomla');
            echo TTHelper::showTorTagsTag('CSS');
            ?>
        </div>
    <?php
    }

}
