<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

class TTHelper
{

    public $settings = null;

    function __construct()
    {
        $this->settings = JComponentHelper::getParams('com_tortags');
    }

    public function setMetaKeysToTags($metakey = null, $option = '', $aid = 0)
    {
        if (sizeof($metakey)) {
            $db = JFactory::getDBO();
            $metakeys = explode(',', $metakey);
            if (sizeof($metakeys)) {
                $query = $db->getQuery(true);
                $query->select('`id`,`def_lang`');
                $query->from('`#__tortags_components`');
                $query->where('`component`=' . $db->quote($option));
                $db->setQuery($query);
                $compnt = $db->loadObject();
                $oid = $compnt->id;
                $dl = $compnt->def_lang ? $compnt->def_lang : '*';

                foreach ($metakeys as $metakey) {
                    $metakey = trim($metakey);
                    if (!$metakey)
                        continue;
                    $query = $db->getQuery(true);
                    $query->select('`id`');
                    $query->from('`#__tortags_tags`');
                    $query->where('`title`=' . $db->quote($metakey));
                    $db->setQuery($query);
                    $tid = $db->loadResult();
                    if (!$tid) {
                        $object = new stdclass();
                        $object->title = $metakey;
                        $object->alias = TTHelper::stringMainURLSafe($metakey);
                        $object->language = $dl;
                        $object->published = 1;
                        $db->insertObject('#__tortags_tags', $object);
                        $tid = (int)$db->insertid();
                    }

                    $query = $db->getQuery(true);
                    $query->select('`id`');
                    $query->from('`#__tortags`');
                    $query->where('`tid`=' . $tid);
                    $query->where('`oid`=' . $oid);
                    $query->where('`item_id`=' . $aid);
                    $db->setQuery($query);
                    $id = $db->loadResult();
                    if ($id) {
                        //already exist
                    } else {
                        $object = new stdclass();
                        $object->tid = $tid;
                        $object->oid = $oid;
                        $object->item_id = $aid;
                        if ($db->insertObject('#__tortags', $object)) {

                        }
                    }
                }
            }
        }
        return;
    }

    public static function getTorTagsResultsByCatId($cid = 0)
    {
        $cid = (int)$cid;
        $results = null;
        if (!$cid)
            return null;

        $tags = TTHelper::getTagsByCat($cid);

        if (sizeof($tags)) {
            $results = $tags_array = array();

            foreach ($tags as $t) {
                $tags_array[] = $t->title;
            }

            if (sizeof($tags_array)) {
                $tstr = implode(',', $tags_array);
                $results = TTHelper::getTagResults($tstr);
            }

            // delete dublicates
            if (sizeof($results)) {
                $add_mass = array();
                foreach ($results as $k => $res) {
                    if (in_array($res->id . $res->title, $add_mass))
                        unset($results[$k]);
                    else
                        $add_mass[] = $res->id . $res->title;
                }
                $results = array_values($results);
            }
        }
        return $results;
    }

    public static function dateTransform($date = 0)
    {
        if (is_numeric($date))
            return $date;
        else
            return (strtotime($date));
    }

    public static function sortResultData($data = null, $e = 'created', $z = 'ASC')
    {
        $size = count($data) - 1;
        for ($i = $size; $i >= 0; $i--) {
            for ($j = 0; $j <= ($i - 1); $j++) {
                if ($e == 'created') {
                    $fs = TTHelper::dateTransform($data[$j]->$e);
                    $ss = TTHelper::dateTransform($data[$j + 1]->$e);
                } else {
                    $fs = strtolower($data[$j]->$e);
                    $ss = strtolower($data[$j + 1]->$e);
                }

                if ($z == 'DESC') {
                    if ($fs < $ss) {
                        $k = $data[$j];
                        $data[$j] = $data[$j + 1];
                        $data[$j + 1] = $k;
                    }
                } else {
                    if ($fs > $ss) {
                        $k = $data[$j];
                        $data[$j] = $data[$j + 1];
                        $data[$j + 1] = $k;
                    }
                }
            }
        }
        return $data;
    }

    public static function getPrepareTagResults($results = null, $tag = '', $start = 0, $add_params = array())
    {
        if (!sizeof($results))
            return null;

        $settings = JComponentHelper::getParams('com_tortags');
        $items = null;

        $limit = $settings->get('result_limit', 5);
        $order = $settings->get('result_ordering', 'created');
        $order_type = $settings->get('result_ordering_type', 'ASC');
        $enabledtags = $settings->get('result_tags_enabled', 1);
        $enabledhigh = $settings->get('result_show_enable_highlighting', 1);

        $usesearchprepare = $settings->get('result_use_search_prepare', 1);

        //redefine settings (for addition parts: modules etc)
        if (sizeof($add_params)) {
            $limit = (isset($add_params['result_limit']) ? $add_params['result_limit'] : $limit);
            $order = (isset($add_params['result_ordering']) ? $add_params['result_ordering'] : $order);
            $order_type = (isset($add_params['result_ordering_type']) ? $add_params['result_ordering_type'] : $order_type);
            $enabledtags = (isset($add_params['result_tags_enabled']) ? $add_params['result_tags_enabled'] : $enabledtags);

            $usesearchprepare = (isset($add_params['result_use_search_prepare']) ? $add_params['result_use_search_prepare'] : $usesearchprepare);
        }

        if ($usesearchprepare && $tag)
            require_once JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php';

        $results = TTHelper::sortResultData($results, $order, $order_type);

        for ($i = 0, $count = count($results); $i < $count; $i++) {
            if ($tag) {
                if (($i + 1) <= $start) {
                    continue;
                }
                if (($i + 1) > ($start + $limit)) {
                    continue;
                }
            } else {
                if (($i + 1) <= $start) {
                    continue;
                }
                if (($i + 1) > ($start + $limit)) {
                    continue;
                }
            }

            $row = &$results[$i]->text;
            $res = &$results[$i];

            if ($tag) {

                if ($usesearchprepare) {
                    $row = SearchHelper::prepareSearchContent($row, $tag);
                }

                $searchwords = preg_split("/\s+/u", $tag);
                $searchwords = array_unique($searchwords);
                $searchRegex = '#(';
                $x = 0;

                foreach ($searchwords as $k => $hlword) {
                    $searchRegex .= ($x == 0 ? '' : '|');
                    $searchRegex .= preg_quote($hlword, '#');
                    $x++;
                }
                $searchRegex .= ')#iu';

                if ($enabledhigh)
                    $row = preg_replace($searchRegex, '<span class="highlight">\0</span>', $row);
            }

            if ($res->cid && $enabledtags) {
                $row .= '<div class="tortags-tags">{tortags,' . $res->id . ',' . $res->cid . '}</div>';
            }

            $row = JHtml::_('content.prepare', $row);

            if (!isset($res->created)) {
                $res->created = '';
            }
            $items[] = $res;
        }
        return $items;
    }

    public static function getTagResults($tag = '', $add_params = array())
    {
        if ($tag == '') {
            return null;
        }

        jimport('joomla.utilities.date');

        $return = null;
        $is_multiple = false;

        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $date = JFactory::getDate();

        $nullDate = $db->getNullDate();

        if (method_exists($date, 'toSql'))
            $now = $date->toSql();
        else
            $now = date('Y-m-d H:i:s');

        $settings = JComponentHelper::getParams('com_tortags');

        if (!sizeof($add_params)) {
            $strong_search = (JRequest::getVar('tt_strong') == 'on' ? true : false);
            $exact = $settings->get('search_exact', 0);
            $is_multilang = (int)$settings->get('multilang_mode_enabled', 0);
            $update_hits = true;
        } else {
            $strong_search = (isset($add_params['strong_search']) ? $add_params['strong_search'] : false);
            $exact = (isset($add_params['search_exact']) ? $add_params['search_exact'] : false);
            $update_hits = (isset($add_params['update_hits']) ? $add_params['update_hits'] : true);
            $is_multilang = (isset($add_params['multilang_mode_enabled']) ? $add_params['multilang_mode_enabled'] : true);
        }

        $tag = TTHelper::replaceSpecSymbInTag($tag, 'decode');
        //$tag=str_replace('?', '\?', $tag);
        //multiple comma separated tags
        $tarray = explode(',', $tag);
        $mainquery = '';

        $mainquery = $db->getQuery(true);
        if (sizeof($tarray)) {
            $is_multiple = true;
            foreach ($tarray as $ta) {
                if ($exact) {
                    $word = $db->Quote($db->escape($ta, true), false);
                    $where = '`t`.`title` LIKE ' . $word;
                } else {
                    $words = explode(' ', $ta);
                    $wheres = array();
                    foreach ($words as $word) {
                        $word = $db->Quote('%' . $db->escape($word, true) . '%', false);
                        $wheres2 = array();
                        $wheres2[] = '`t`.`title` LIKE ' . $word;
                        $wheres[] = implode(' OR ', $wheres2);
                    }
                    $where = '(' . implode(') OR (', $wheres) . ')';
                }
                $mainquery->where($where, 'OR');
            }

            $strqr = $mainquery->__get('where')->__toString();
            $strqr = substr($strqr, 7, strlen($strqr));
        }
        //

        if ($update_hits) {
            //UPDATE HITS
            $db->setQuery('UPDATE `#__tortags_tags` SET `hits`=hits+1 WHERE `title`=' . $db->quote($tag));
            $db->query();
            //
        }

        $query = $db->getQuery(true);
        $query->select('t.id');
        $query->from('#__tortags_tags AS t');
        if ($is_multiple) {
            $query->where($strqr);
        } else {
            //1 tag search
            if ($exact) {
                $word = $db->Quote($db->escape($tag, true), false);
                $where = '`t`.`title` LIKE ' . $word;
            } else {
                $words = explode(' ', $tag);
                $wheres = array();
                foreach ($words as $word) {
                    $word = $db->Quote('%' . $db->escape($word, true) . '%', false);
                    $wheres2 = array();
                    $wheres2[] = '`t`.`title` LIKE ' . $word;
                    $wheres[] = implode(' OR ', $wheres2);
                }
                $where = '(' . implode(') OR (', $wheres) . ')';
            }
            $query->where($where);
        }
        if ($is_multilang)
            $query->where('`t`.`language` in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

        $query->where('`t`.`published`=1');
        $db->setQuery($query);

        $tagids = $db->loadColumn();

        if (!sizeof($tagids)) {
            return null;
        }

        $tagids = array_unique($tagids);

        $ids = implode(',', $tagids);
        /*
          $query = $db->getQuery(true);
          $query->select('m.oid');
          $query->from('#__tortags AS m');
          $query->where('m.tid IN ('.$ids.')');
          $query->group('m.oid');
          $db->setQuery($query);
          $oids = $db->loadColumn();
          if(!sizeof($oids)) {return null;}
         */

        $oids = array();

        foreach ($tagids as $tid) {
            $oids_tmp = array();
            $query = $db->getQuery(true);
            $query->select('m.oid');
            $query->from('#__tortags AS m');
            $query->where('m.tid =' . $tid);
            $db->setQuery($query);
            $oids_tmp = $db->loadColumn();
            if (!sizeof($oids_tmp)) {
                return null;
            }
            if (!sizeof($oids))
                $oids = $oids_tmp;
            else {
                if ($is_multiple && $strong_search) {
                    $oids = array_intersect($oids, $oids_tmp);
                } else {
                    $oids = array_merge($oids, $oids_tmp);
                }
            }
        }
        $oids = array_unique($oids);

        foreach ($oids as $oid) {
            /*
              $query = $db->getQuery(true);
              $query->select('m.item_id');
              $query->from('#__tortags AS m');
              $query->where('m.tid IN ('.$ids.')');
              $query->where('m.oid='.$oid);
              $db->setQuery($query);
              $itemids = $db->loadColumn();
             */

            $itemids = array();
            foreach ($tagids as $tid) {
                $items_tmp = array();
                $query = $db->getQuery(true);
                $query->select('m.item_id');
                $query->from('#__tortags AS m');
                $query->where('m.tid =' . $tid);
                $query->where('m.oid=' . $oid);
                if (sizeof($itemids) && $is_multiple && $strong_search) {
                    $query->where('m.item_id IN (' . implode(',', $itemids) . ')');
                }
                $db->setQuery($query);
                $items_tmp = $db->loadColumn();
                if (!sizeof($items_tmp) && $is_multiple && $strong_search) {
                    $itemids = array();
                    break;
                } else {
                    if (!sizeof($itemids)) {
                        $itemids = $items_tmp;
                    } else {
                        if ($is_multiple && $strong_search) {
                            $itemids = array_intersect($items_tmp, $itemids);
                        } else {
                            $itemids = array_merge($items_tmp, $itemids);
                        }
                    }
                }
            }
            $itemids = array_unique($itemids);

            if (sizeof($itemids)) {
                $query = $db->getQuery(true);
                $query->select('c.*');
                $query->from('#__tortags_components AS c');
                $query->where('c.id =' . $oid);
                $db->setQuery($query);
                $param = $db->loadObject();
                if ($param->table) {
                    if ($param->table != '#__') {
                        $query = $db->getQuery(true);
                        $table = $param->table;
                        $id_field = ($param->id_field ? ('`t`.`' . $param->id_field . '`') : 'id');
                        $title = ($param->title_field ? ('`t`.`' . $param->title_field . '`') : '""');
                        $description = ($param->description_field ? ('`t`.`' . $param->description_field . '`') : '""');
                        $created = ($param->created_field ? ('`t`.`' . $param->created_field . '`') : '""');
                        $query->select($id_field . ' AS `id`');
                        $query->select($title . ' AS `title`');
                        $query->select($description . ' AS `text`');
                        $query->select($created . ' AS `created`');
                        if ($param->component == 'com_content') {
                            $query->select('`t`.`catid`');
                            $query->select('`t`.`alias`');
                            $query->select('`t`.`images`');
                            //$query->where('`t`.`state`=1');
                            $groups = implode(',', $user->getAuthorisedViewLevels());
                            $query->where('`t`.`access` IN (' . $groups . ')');
                            $query->where('(`t`.`state`=1 OR `t`.`state`=2)');
                            $query->where('(`t`.`publish_up` = ' . $db->Quote($nullDate) . ' OR `t`.`publish_up` <= ' . $db->Quote($now) . ') ');
                            $query->where('(`t`.`publish_down` = ' . $db->Quote($nullDate) . ' OR `t`.`publish_down` >= ' . $db->Quote($now) . ')');
                            $query->where('`t`.`language` in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                            $query->select('`c`.`title` AS `cat_title`');
                            $query->leftjoin('#__categories AS c ON c.id=t.catid AND c.access IN (' . $groups . ')');
                            $query->where(' (c.published = 1 AND c.access IN (' . $groups . '))');
                        }
                        if ($param->component == 'com_categories') {
                            $query->select('`alias`');
                            $query->select('`extension`');
                            $query->where('`t`.`published`=1');
                        }
                        if ($param->component == 'com_k2') {
                            $query->select('`alias`');
                            $query->select('`catid`');
                        }
                        if ($param->component == 'com_easyblog' || $param->component == 'com_easydiscuss') {
                            $query->where('`t`.`published`=1');
                        }
                        if ($param->component == 'com_marketplace') {
                            $query->select('`category_id` AS `catid`');
                        }

                        if ($param->component == 'com_jshopping') {
                            $query->select('(SELECT `jpc`.`category_id` FROM `#__jshopping_products_to_categories` AS `jpc` WHERE `jpc`.`product_id`=`t`.`product_id` LIMIT 1) AS `catid`');
                        }

                        if ($param->component == 'com_joomgallery') {
                            //for joomgall categories
                            if ($param->id_field == 'cid') {
                                $query->select('(SELECT `jg`.`imgthumbname` FROM `#__joomgallery` AS `jg` WHERE `jg`.`id`=`t`.`thumbnail`) AS `jgall_image`');
                                $query->select('`catpath`');
                            } else {
                                $query->select('(SELECT `jgc`.`catpath` FROM `#__joomgallery_catg` AS `jgc` WHERE `jgc`.`cid`=`t`.`catid`) AS `catpath`');
                                $query->select('`imgthumbname` AS `jgall_image`');
                            }
                        }
                        if ($param->component == 'com_sobipro') {
                            $query->leftjoin('`#__sobipro_field_data` AS `sbpd` ON `sbpd`.`sid`=`t`.`id` AND `sbpd`.`fid`=1');
                            $query->select('`sbpd`.`baseData` AS `title`');
                            $query->select('`parent` AS `pid`');
                            $created = 'created';
                        }
                        $query->where($id_field . ' IN (' . implode(',', $itemids) . ')');
                        $query->from($table . ' AS `t`');
                        $db->setQuery($query);
                        $res = $db->loadObjectList();

                        if (sizeof($res)) {
                            foreach ($res as $r) {

                                $a = $b = array();

                                $a[] = '[COMPONENT]';
                                $b[] = $param->component;
                                $a[] = '[ID]';
                                $b[] = $r->id;
                                $a[] = '[CATID]';
                                $b[] = (isset($r->catid) ? $r->catid : null);
                                $url = str_replace($a, $b, $param->url_template);

                                switch ($param->component) {
                                    case 'com_sobipro':
                                        jimport('joomla.filter.output');
                                        $alias = JFilterOutput::stringURLSafe($r->title);
                                        $url .= '&pid=' . $r->pid;
                                        $url = str_replace($r->id, $r->id . ':' . $alias, $url);
                                        break;
                                }

                                $app = JFactory::getApplication();
                                $menu = $app->getMenu();
                                //$menu = JSite::getMenu();
                                $item = $menu->getItems('link', $url, true);
                                $Itemid = JRequest::getInt('Itemid');
                                $find = false;
                                if (isset($item)) {
                                    if (is_object($item))
                                        $Itemid = $item->id;
                                    else
                                        $find = true;
                                } else
                                    $find = true;

                                if ($find) {
                                    $query_menu = $db->getQuery(true);
                                    $query_menu->select('`id`');
                                    $query_menu->from('`#__menu`');
                                    //$query_menu->where('`menutype`="mainmenu"');
                                    $query_menu->where('`link` like "index.php?option=' . $param->component . '%"');
                                    $query_menu->where('`published`="1"');
                                    $db->setQuery($query_menu);
                                    $citemid = $db->loadResult();

                                    if (!isset($citemid)) {
                                        if (JRequest::getInt('Itemid') > 0) {
                                            $Itemid = JRequest::getInt('Itemid');
                                        }
                                    } else
                                        $Itemid = (int)$citemid;
                                }

                                if (isset($Itemid))
                                    if ($Itemid)
                                        $url .= '&Itemid=' . $Itemid;

                                switch ($param->component) {
                                    case 'com_content':
                                        require_once(JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php');
                                        $r->slug = $r->alias ? ($r->id . ':' . $r->alias) : $r->id;
                                        $r->href = ContentHelperRoute::getArticleRoute($r->slug ? $r->slug : $r->id, $r->catid);
                                        $r->images = json_decode($r->images);
                                        break;

                                    case 'com_category':
                                        require_once(JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php');
                                        $r->href = ContentHelperRoute::getCategoryRoute($r->id);
                                        break;

                                    case 'com_categories':

                                        if ($r->extension == 'com_content') {
                                            $Itemid = (int)$menu->getDefault(JFactory::getLanguage()->getTag())->id;
                                            require_once(JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php');
                                            $r->href = JRoute::_(ContentHelperRoute::getCategoryRoute($r->id) . '&Itemid=' . $Itemid);
                                        } else
                                            if ($r->extension == 'com_weblinks') {
                                                $query_menu = $db->getQuery(true);
                                                $query_menu->select('`id`');
                                                $query_menu->from('`#__menu`');
                                                $query_menu->where('`link` like "index.php?option=com_weblinks&view=categories%"');
                                                $query_menu->where('`published`="1"');
                                                $db->setQuery($query_menu);
                                                $Itemid = $db->loadResult();
                                                if (!$Itemid)
                                                    $Itemid = 0;
                                                require_once(JPATH_SITE . DS . 'components' . DS . 'com_weblinks' . DS . 'helpers' . DS . 'route.php');
                                                $weblink = WeblinksHelperRoute::getCategoryRoute($r->id);
                                                $r->href = JRoute::_($weblink . '&Itemid=' . $Itemid);
                                                //$r->href = JRoute::_($weblink);
                                            } else

                                                if ($r->extension == 'com_newsfeeds') {
                                                    $query_menu = $db->getQuery(true);
                                                    $query_menu->select('`id`');
                                                    $query_menu->from('`#__menu`');
                                                    $query_menu->where('`link` like "index.php?option=com_newsfeeds%"');
                                                    $query_menu->where('`published`="1"');
                                                    $db->setQuery($query_menu);
                                                    $Itemid = $db->loadResult();
                                                    if (!$Itemid)
                                                        $Itemid = (int)$menu->getDefault(JFactory::getLanguage()->getTag())->id;
                                                    require_once(JPATH_SITE . DS . 'components' . DS . 'com_newsfeeds' . DS . 'helpers' . DS . 'route.php');
                                                    $newsfeedlink = NewsfeedsHelperRoute::getCategoryRoute($r->id);
                                                    $r->href = JRoute::_($newsfeedlink . '&Itemid=' . $Itemid);
                                                } else {
                                                    $r->href = JRoute::_($url);
                                                    break;
                                                }
                                        break;

                                    case 'com_k2':
                                        require_once(JPATH_SITE . DS . 'components' . DS . 'com_k2' . DS . 'helpers' . DS . 'route.php');
                                        $r->href = JRoute::_(K2HelperRoute::getItemRoute($r->id . ':' . urlencode($r->alias), $r->catid));
                                        break;
                                    default:
                                        $r->href = JRoute::_($url);
                                        break;
                                }
                                //$r->component = $param->component;
                                $r->cid = $param->id;

                                $return[] = $r;
                            }
                        }
                    }
                }
            }
        }
        return $return;
    }

    public static function getTags($id, $option, $oid = null, $tinel_order = '', $is_multilang = false)
    {
        $db = JFactory::getDBO();

        if (!$oid) {
            $query = $db->getQuery(true);
            $query->select('`id`');
            $query->from('`#__tortags_components`');
            $query->where('`component`=' . $db->quote($option));
            $db->setQuery($query);
            $oid = $db->loadResult();
        }

        $query = $db->getQuery(true);
        $query->select('`t`.*');
        $query->from('`#__tortags_tags` AS `t`');
        $query->join('INNER', '`#__tortags` AS `m` ON `m`.`tid`=`t`.`id`');
        $query->where('`m`.`item_id`=' . (int)$id);
        $query->where('`m`.`oid`=' . (int)$oid);
        if ($is_multilang)
            $query->where('`t`.`language` in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
        if ($tinel_order) {
            if ($tinel_order != 'RAND')
                $query->order('`t`.`title` ' . $tinel_order);
            else
                $query->order('RAND()');
        }
        $query->where('`t`.`published`=1');
        $db->setQuery($query);
        $tags = $db->loadObjectList();
        return $tags;
    }

    public static function getItemIdForTorTags()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('`id`');
        $query->from('`#__menu`');
        $query->where('`link`=' . $db->quote('index.php?option=com_tortags&view=default'));
        $query->where('`published`=1');
        $db->setQuery($query);
        $Itemid = $db->loadResult();
        if (!$Itemid) {
            $query = $db->getQuery(true);
            $query->select('`id`');
            $query->from('`#__menu`');
            $query->where('`link` LIKE ' . $db->quote('%index.php?option=com_tortags%'));
            $query->where('`published`=1');
            $db->setQuery($query);
            $Itid = $db->loadResult();
            $Itemid = ($Itemid ? $Itemid : ($Itid ? ('&Itemid=' . $Itid) : ''));
            /* 		if (!$Itemid)
              {

              $menu	= & JApplication::getMenu('site');
              $Itemid = (isset($menu->getActive()->id))?('&Itemid='.$menu->getActive()->id):'';
              $Itid = JRequest::getInt('Itemid');
              $Itemid = ($Itemid?$Itemid:($Itid?('&Itemid='.$Itid):''));
              }
             */
        } else {
            $Itemid = '&Itemid=' . $Itemid;
        }
        return $Itemid;
    }

    public static function getCatsByTags($tags = null)
    {
        $tids = array();
        if (sizeof($tags)) {
            foreach ($tags as $tag) {
                $tids[] = (int)$tag->id;
            }
            if (sizeof($tids)) {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('`c`.*');
                $query->from('`#__tortags_cat_xref` AS `x`');
                $query->leftjoin('`#__tortags_categories` AS `c` ON `c`.`id`=`x`.`cid`');
                $query->where('`x`.`tid` IN (' . implode(',', $tids) . ')');
                $query->where('`c`.`published`=1');
                $query->order('`c`.`catname`');
                $query->group('`c`.`id`');
                $db->setQuery($query);
                return $db->loadObjectList();
            }
        }
        return null;
    }

    public static function getTagsByCat($cid = 0)
    {
        $gsettings = JComponentHelper::getParams('com_tortags');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('`t`.*');
        $query->from('`#__tortags_cat_xref` AS `x`');
        $query->leftjoin('`#__tortags_tags` AS `t` ON `t`.`id`=`x`.`tid`');

        if ($gsettings->get('multilang_mode_enabled', 0) == 1) {
            $query->where('`t`.`language` in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
        }
        $query->where('`x`.`cid`=' . (int)$cid);
        $query->order('`t`.`id`');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public static function replaceSpecSymbInTag($tag = '', $way = 'enc')
    {
        $gsettings = JComponentHelper::getParams('com_tortags');
        $tt_specs = $gsettings->get('url_spec_symbols', '');
        $tt_replcs = $gsettings->get('url_spec_replacers', '');

        $a = $b = array();
        $a[] = '+';
        $b[] = '{plus}';
        $a[] = '@';
        $b[] = '{at}';
        $a[] = '#';
        $b[] = '{sharp}';
        $a[] = '&';
        $b[] = '{and}';
        $a[] = '?';
        $b[] = '{question}';
        $a[] = '%';
        $b[] = '{percent}';
        $a[] = ':';
        $b[] = '{colon}';
        $a[] = '/';
        $b[] = '{slash}';
        if ($tt_specs && $tt_replcs) {
            $tt_specs = explode(',', $tt_specs);
            $tt_replcs = explode(',', $tt_replcs);
            if (sizeof($tt_specs) && sizeof($tt_replcs)) {
                for ($i = 0; $i < count($tt_specs); $i++) {
                    if (isset($tt_replcs[$i])) {
                        $a[] = $tt_specs[$i];
                        $b[] = $tt_replcs[$i];
                    }
                }
            }
        }

        if ($way == 'enc') {
            return str_replace($a, $b, $tag);
        } else {
            return str_replace($b, $a, $tag);
        }
    }

    public static function generateTagLink($tag = '')
    {
        $settings = JComponentHelper::getParams('com_tortags');
        $deltaginurl = $settings->get('del_tag_in_url');

        $tag = TTHelper::tagSafety(TTHelper::replaceSpecSymbInTag($tag));
        $Itemid = TTHelper::getItemIdForTorTags();

        $vadd = '&view=tag';
        if ($deltaginurl)
            $vadd = '';

        $link = JRoute::_('index.php?option=com_tortags' . $vadd . '&tag=' . $tag . $Itemid);
        return $link;
    }

    public static function tagSafety($tag = '')
    {
        return trim(substr($tag, 0, 125));
    }

    public static function catSafety($cat = '')
    {
        return trim(substr($cat, 0, 150));
    }

    public static function showTorTagsCategory($catname = '', $show_link = false, $catid = 0)
    {
        $catname = TTHelper::catSafety($catname);
        if ($show_link) {
            $Itemid = TTHelper::getItemIdForTorTags();
            $link = JRoute::_('index.php?option=com_tortags&view=category&id=' . $catid . $Itemid);
            return '<span class="tt_cat"><a href="' . $link . '" title="' . $catname . '">' . $catname . '</a></span>';
        } else
            return '<span class="tt_cat">' . $catname . '</span>';
    }

    public static function showTorTagsTag($tag = '', $tid = '', $tdescr = '', $enbl_toolt = 1)
    {
        if (!$tag)
            return;
        $tag = TTHelper::replaceSpecSymbInTag($tag, 'decode');

        $tag_id = '';
        if ($tid)
            $tag_id = 'id="' . $tid . '"';

        $tag_link = TTHelper::generateTagLink($tag);

        $tag = TTHelper::tagSafety($tag);

        $tag_full = $tag;
        if ($enbl_toolt) {
            if ($tdescr) {
                $tag_full = JHTML::tooltip($tdescr, $tag, '', $tag);
                $tag = '';
            } else {
                //$tag_full = JHTML::tooltip($tag,$tag,'',$tag);
                //$tag='';
            }
        }
        $return = '<div ' . $tag_id . ' class="tt_button">' .
            '<div class="tt_end">' .
            '<a class="tt_link" href="' . $tag_link . '" title="' . $tag . '">' .
            '<span class="tt_img">&nbsp;</span>' .
            '<span class="tttag">' . $tag_full . '</span>' .
            '<span class="tt_img_end">&nbsp;</span>' .
            '</a>' .
            '</div>' .
            '</div>';
        return $return;
    }

    public static function showclass($l)
    {
        $letter = JRequest::getVar('letter');
        if (!$letter || !$l)
            return;
        //if (htmlspecialchars(trim(strtolower($letter)))==$l) return 'class="tt-alpsel"';
        if (htmlspecialchars(trim($letter)) == $l)
            return 'class="tt-alpsel"';
    }

    public static function getAlphabeticalList($link = '')
    {
        $settings = JComponentHelper::getParams('com_tortags');
        //$letters = explode(',',trim(htmlspecialchars($settings->get('alphabet_list','A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z'))));
        $letters = explode(',', trim(htmlspecialchars(JText::_('COM_TORTAGS_TAG_ALPHABET_LIST_SYMBOLS'))));
        $add_li = '';
        if (sizeof($letters)) {
            foreach ($letters as $letter) {
                if ($letter) {
                    $add_li .= '<li ' . TTHelper::showclass($letter) . '><a href="' . $link . $letter . '">' . $letter . '</a></li>' . "\n";
                }
            }
        }
        ob_start();
        ?>
        <ul class="tt-alphabet">
            <?php echo $add_li; ?>
            <li <?php echo TTHelper::showclass('cifr'); ?>><a href="<?php echo JRoute::_($link . 'cifr'); ?>">0-9</a>
            </li>
            <li <?php echo TTHelper::showclass('all'); ?>><a href="<?php echo JRoute::_($link . 'all'); ?>">*</a></li>
        </ul>
        <?php
        $list = ob_get_contents();
        ob_end_clean();
        return $list;
    }

    public static function showResultBox($result = null, $number = 0, $add_params = null)
    {
        $settings = JComponentHelper::getParams('com_tortags');

        if (!$add_params) {
            $showinfo = $settings->get('result_date_enabled', 1);
            $newpage = $settings->get('result_open_type', 0);
            $show_numbers = $settings->get('result_show_numbers', 0);
            $show_readmore = $settings->get('result_show_readmore', 0);
            $readmore_text = $settings->get('result_readmore_text', 'Read more');
            $show_description = true;
            $show_images = true;
        } else {
            $showinfo = (isset($add_params['result_date_enabled']) ? $add_params['result_date_enabled'] : true);
            $newpage = (isset($add_params['result_open_type']) ? $add_params['result_open_type'] : false);
            $show_numbers = (isset($add_params['result_show_numbers']) ? $add_params['result_show_numbers'] : false);
            $show_readmore = (isset($add_params['result_show_readmore']) ? $add_params['result_show_readmore'] : false);
            $readmore_text = (isset($add_params['result_readmore_text']) ? $add_params['result_readmore_text'] : 'Read more');
            $show_description = (isset($add_params['show_description']) ? $add_params['show_description'] : false);
            $show_images = (isset($add_params['show_images']) ? $add_params['show_images'] : false);
        }
        $showcat = $settings->get('show_contet_cat_in_results', 0);

        ob_start();
        ?>
        <div class="tortags-result-box">
            <div class="tortags-result-box-title">
                <div class="tortags-result-title">
                    <?php if ($show_numbers) echo $number . '. '; ?>
                    <?php if ($result->href) { ?>
                        <a href="<?php echo JRoute::_($result->href); ?>"<?php if ($newpage) { ?> target="_blank"<?php } ?>>
                            <?php echo htmlspecialchars($result->title); ?>
                        </a>
                    <?php } else {
                        ?>
                        <?php echo htmlspecialchars($result->title); ?>
                    <?php } ?>
                </div>
                <?php if (isset($result->created) && $showinfo) { ?>
                    <div class="tortags-info">
                        <?php
                        if (isset($result->created)) {
                            if ($result->created) {
                                if ($result->created == '0000-00-00 00:00:00') {
                                    $result->created = null;
                                } else
                                    $result->created = JHtml::_('date', $result->created, JText::_('DATE_FORMAT_LC3'));
                            }
                            echo($result->created ? JText::sprintf('JGLOBAL_CREATED_DATE_ON', $result->created) : '');
                        }
                        ?>

                    </div>
                <?php } ?>
            </div>
            <?php if (isset($result->cat_title) && $showcat) { ?>
                <div class="tortags-cat">
                    (<?php echo $result->cat_title; ?>)
                </div>
            <?php } ?>
            <?php
            if ($show_images) {
                if (isset($result->jgall_image)) {
                    //temp
                    $db = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    $query->select('jg_paththumbs');
                    $query->from('`#__joomgallery_config`');
                    $db->setQuery($query);
                    $path = $db->loadResult();
                    //
                    echo '<div class="tt-result-image"><img src="' . $path . $result->catpath . '/' . $result->jgall_image . '" alt="" /></div>';
                }
                ?>
                <!-- only for intro content image -->
                <?php if (isset($result->images->image_intro) and !empty($result->images->image_intro)) : ?>
                    <div class="tt-result-image">
                        <?php
                        $imgfloat = $result->images->float_intro;
                        $imcaption = htmlspecialchars($result->images->image_intro_caption);
                        ?>
                        <div class="img-intro-<?php echo htmlspecialchars($imgfloat); ?>">
                            <img
                                <?php
                                if ($imcaption) {
                                    echo 'class="caption"' . ' title="' . $imcaption . '"';
                                }
                                ?>
                                src="<?php echo htmlspecialchars($result->images->image_intro); ?>"
                                alt="<?php echo htmlspecialchars($result->images->image_intro_alt); ?>"/>
                            <?php if ($imcaption) { ?> <p class="tt-intor-cpt"><?php echo $imcaption; ?></p> <?php } ?>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- -->
            <?php
            }

            if ($show_description) {
                ?>
                <div class="tortags-result-text">
                    <?php echo $result->text; ?>
                </div>
            <?php
            }

            if ($show_readmore) {
                ?>
                <div class="tt_readmore">
                    <a class="readmore"
                       href="<?php echo JRoute::_($result->href); ?>"<?php if ($newpage) { ?> target="_blank"<?php } ?>>
                        <?php echo JText::_($readmore_text); ?>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
        <?php
        $return = ob_get_contents();
        ob_end_clean();
        return $return;
    }

    /*         * *SEF** */

    static public function stringMainURLSafe($string)
    {
        $tortags_settings = JComponentHelper::getParams('com_tortags');
        $symbol = $tortags_settings->get('alias_replace_symbol', '_');

        if (JFactory::getConfig()->get('unicodeslugs') == 1) {
            $output = TTHelper::stringURLUnicodeSlug($string, $symbol);
        } else {
            $output = TTHelper::stringURLSafe($string, $symbol);
        }
        if (!$output) $output = date('Y_m_d_his');
        return $output;
    }

    public static function stringURLSafe($string, $symbol = "_")
    {
        // remove any $symbol from the string since they will be used as concatenaters
        $str = str_replace($symbol, ' ', $string);

        $lang = JFactory::getLanguage();
        $str = $lang->transliterate($str);

        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(JString::strtolower($str));

        // Remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', $symbol, $str);

        // Trim dashes at beginning and end of alias
        $str = trim($str, $symbol);

        return $str;
    }

    public static function stringURLUnicodeSlug($string, $symbol = "_")
    {
        // Replace double byte whitespaces by single byte (East Asian languages)
        $str = preg_replace('/\xE3\x80\x80/', ' ', $string);

        // Remove any '-' from the string as they will be used as concatenator.
        // Would be great to let the spaces in but only Firefox is friendly with this

        $str = str_replace($symbol, ' ', $str);

        // Replace forbidden characters by whitespaces
        $str = preg_replace('#[:\#\*"@+=;!><&\.%()\]\/\'\\\\|\[]#', "\x20", $str);

        // Delete all '?'
        $str = str_replace('?', '', $str);

        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(JString::strtolower($str));

        // Remove any duplicate whitespace and replace whitespaces by hyphens
        $str = preg_replace('#\x20+#', $symbol, $str);

        return $str;
    }

    /*         * ** */
}

    