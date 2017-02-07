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
$return = '';
$link = 'index.php?option=com_tortags&view=default&letter=';

$settings = JComponentHelper::getParams('com_tortags');
if ($settings->get('show_alpha')) {
    echo TTHelper::getAlphabeticalList($link);
}
$tags = $this->tags;

$return .= '<div class="tt-cat-tags">';
if (sizeof($tags)) {
    foreach ($tags as $tag) {
        $return .= '<div class="tt-tag-list">';
        $return .= TTHelper::showTorTagsTag($tag->title, "tagid_" . $tag->id);
        $return .= '</div>';
    }
}
$return .= '</div>';
echo $return;
?>
<div class="pagination tt-center">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>