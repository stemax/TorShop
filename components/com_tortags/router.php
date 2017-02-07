<?php

/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

function TortagsBuildRoute(&$query)
{
    $segments = array();
    $view = '';
    if (isset($query['view'])) {
        $view = $query['view'];
        if (empty($query['Itemid']) && (!isset($query['tag']))) {
            $segments[] = $query['view'];
        }
        $segments[] = $query['view'];
        unset($query['view']);
    }
    if (isset($query['id'])) {
        $db = JFactory::getDbo();
        $db->setQuery($db->getQuery(true)
            ->select('`alias`')
            ->from('`#__tortags_categories`')
            ->where('`id`=' . (int)$query['id'])
        );
        $alias = $db->loadResult();
        if ($alias)
            $query['id'] = $alias;

        $segments[] = $query['id'];
        unset($query['id']);
    } else {
        if ($view == 'category') {
            $segments[] = 'filter';
        }
    }
    if (isset($query['letter'])) {
        $segments[] = $query['letter'];
        unset($query['letter']);
    }
    if (isset($query['tag'])) {
        $db = JFactory::getDbo();
        $db->setQuery($db->getQuery(true)
            ->select('`alias`')
            ->from('`#__tortags_tags`')
            ->where('`title`=' . $db->quote($query['tag']))
        );
        $alias = $db->loadResult();
        if ($alias)
            $query['tag'] = $alias;

        $segments[] = $query['tag'];
        unset($query['tag']);
    }
    if (isset($query['tt_strong'])) {
        unset($query['tt_strong']);
    }
    unset($view);
    return $segments;
}

function TortagsParseRoute($segments)
{
    $vars = array();
    $vars['view'] = $view = $segments[0];
    $db = JFactory::getDbo();

    $settings = JComponentHelper::getParams('com_tortags');
    $deltaginurl = $settings->get('del_tag_in_url');
    if ($deltaginurl)
        if (sizeof($segments) == 1) {
            $vars['view'] = $view = 'tag';
            $segments[1] = $segments[0];
            $segments[0] = 'tag';
        }

    switch ($view) {
        case 'category':
            if (isset($segments[1])) {

                $db->setQuery($db->getQuery(true)
                    ->select('`id`')
                    ->from('`#__tortags_categories`')
                    ->where('`alias`=' . $db->quote(str_replace(':', '-', $segments[1])))
                );
                $title = $db->loadResult();
                if ($title)
                    $vars['id'] = $title;
                else
                    $vars['id'] = $segments[1];
            }
            if (isset($segments[2])) {
                $vars['letter'] = $segments[2];
            }
            break;
        case 'tag':
            if (isset($segments[1])) {
                $segments[1] = str_replace(':', '-', $segments[1]);
                $db->setQuery($db->getQuery(true)
                    ->select('`title`')
                    ->from('`#__tortags_tags`')
                    ->where('`alias`=' . $db->quote($segments[1]))
                );
                $title = $db->loadResult();
                if ($title)
                    $vars['tag'] = $title;
                else
                    $vars['tag'] = $segments[1];
            }
            break;
        case 'default':
            if (isset($segments[1]))
                $vars['letter'] = $segments[1];
            break;
        default:

            break;
    }
    return $vars;
}