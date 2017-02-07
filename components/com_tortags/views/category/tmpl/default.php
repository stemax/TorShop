<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');

jimport('joomla.html.pagination');

$return = '';
$link = 'index.php?option=com_tortags&view=category&letter=';
$document = JFactory::getDocument();

$is_strong = (JRequest::getVar('tt_strong') == 'on' ? true : false);
$is_jsearch = (JRequest::getVar('tt_js') == 1 ? true : false);
$settings = JComponentHelper::getParams('com_tortags');
$limit = $settings->get('result_limit', 5);
$show_count = $settings->get('cats_show_count_elements', 1);
$meta_order = $settings->get('meta_order', 0);
?>
<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="ttSearchForm"
      id="ttSearchForm">
    <?php
    if ($settings->get('cats_show_alpha')) {
        ?>
        <div class="tt-cats-alpha">
            <?php echo TTHelper::getAlphabeticalList($link); ?>
        </div>
    <?php
    }
    if ($settings->get('cats_show_strict')) {
        ?>
        <div class="tt-strong-search-box">
            <input type="checkbox" <?php if ($is_strong) echo 'checked="true" '; ?> name="tt_strong" id="tt_strong"
                   onclick="document.ttSearchForm.submit()"/> <label
                for="tt_strong"><?php echo JText::_('COM_TORTAGS_STRICT_SEARCH'); ?></label>
        </div>
    <?php
    }
    $cats = $this->cats;

    $return .= '<div id="tt-cats" class="tt-cats">';
    if (sizeof($cats)) {
        foreach ($cats as $k => $cat) {
            //add meta
            $meta_keys_old = $document->getMetaData('keywords');
            $meta_desc_old = $document->getMetaData('description');
            $old_title = $document->getTitle();
            $catname = htmlspecialchars($cat->catname);
            if ($k > 0) {
                $comm = $comm_oth = ', ';
            } else {
                $comm = ' - ';
                $comm_oth = ' ';
            }
            if (!$meta_order) {
                $document->setTitle($old_title . $comm . $catname);
                $document->setMetadata('keywords', $meta_keys_old . $comm_oth . $catname);
                $document->setMetadata('description', $meta_desc_old . $comm_oth . $catname);
            } else {
                $document->setTitle($catname . $comm . $old_title);
                $document->setMetadata('keywords', $catname . $comm_oth . $meta_keys_old);
                $document->setMetadata('description', $catname . $comm_oth . $meta_desc_old);
            }
            //
            $allresults = TTHelper::getTorTagsResultsByCatId($cat->id);
            $count_elements = count($allresults);
            $return .= '<div class="tt-category">';

            $return .= '<h1>';
            $return .= '<a href="' . JRoute::_('index.php?option=com_tortags&view=category&id=' . $cat->id) . '" alt="' . $cat->catname . '">';
            $return .= $cat->catname;
            if ($show_count)
                $return .= JText::sprintf('COM_TORTAGS_COUNT_ELEMENTS', $count_elements);
            $return .= '</a>';
            $return .= '</h1>';
            $return .= '<div class="tt-cat-descr">';
            $return .= (isset($cat->description) ? $cat->description : '');
            $return .= '</div>';
            //show tags
            if ($settings->get('cats_showtags', 0)) {
                $cat_tags = TTHelper::getTagsByCat($cat->id);
                $return .= '<div class="tt-tag-list">';
                if (sizeof($cat_tags)) {
                    foreach ($cat_tags as $cat_tag) {
                        $return .= TTHelper::showTorTagsTag($cat_tag->title);
                    }
                } else
                    $return .= JText::_('COM_TORTAGS_ATTACHED_TAGS_NOT_FOUND');
                $return .= '</div>';
            }
            //show elements
            if ($settings->get('cats_show_elements', 1)) {
                $return .= '<div class="tt-result-list">';

                $start = JRequest::getInt('tt_' . $cat->id . 'limitstart');

                $add_params = array();
                $add_params['result_use_search_prepare'] = $is_jsearch;
                //"abcdefgh" for prepare condition
                $results = TTHelper::getPrepareTagResults($allresults, 'abcdefgh', $start, $add_params);
                if (sizeof($results)) {
                    $i = 0;
                    $page = new JPagination($count_elements, $start, $limit, 'tt_' . $cat->id);
                    foreach ($results as $result) {
                        $i++;
                        $n = ($page->limitstart + $i);
                        $return .= TTHelper::showResultBox($result, $n);
                    }
                    $return .= '<div class="pagination tt-center tt-pagenav-results">' . $page->getPagesLinks() . '</div>';
                } else {
                    $return .= JText::_('COM_TORTAGS_ELEMENTS_NOT_FOUND');
                }
                $return .= '</div>';
            }
            //end cat div
            $return .= '</div>';
        }
    }
    $return .= '</div>';
    echo $return;
    ?>
    <div class="pagination tt-center">
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
    <input type="hidden" value="" name="task"/>
    <input type="hidden" value="com_tortags" name="option"/>
</form>