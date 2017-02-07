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
$i = 0;
$settings = JComponentHelper::getParams('com_tortags');
$showinfo = $settings->get('result_date_enabled', 1);
$newpage = $settings->get('result_open_type', 0);
$show_numbers = $settings->get('result_show_numbers', 0);
$can_change = $settings->get('user_can_change', 0);
$is_strong = (JRequest::getVar('tt_strong') == 'on' ? true : false);
$deltaginurl = $settings->get('del_tag_in_url');

$addv = '&view=tag';
if ($deltaginurl)
    $addv = '';

if (isset($this->tag)) {
    ?>
    <script type="text/javascript">
        function ttShowSearchBox() {
            $('tth').set('class', 'tt-hide');
            $('tt-search').set('class', 'tt-show');
        }
    </script>
<?php } ?>
<form
    action="<?php echo JRoute::_('index.php?option=com_tortags' . $addv . '&tag=' . htmlspecialchars(JRequest::getVar('tag'))); ?>"
    method="post" name="ttSearchForm" id="ttSearchForm">
    <div class="tortags-search-results">
        <?php if (isset($this->tag)) {
            ?>
            <h1 class="tth" id="tth"><?php echo $this->tag; ?>
                <?php if ($can_change) { ?>
                    <img class="tt-edit"
                         src="<?php echo JURI::root() . '/components/com_tortags/assets/images/ttedit.png'; ?>" alt=""
                         onclick="javascript:ttShowSearchBox();"/>
                <?php } ?>
            </h1>
        <?php
        }
        if (isset($this->description)) {
            echo '<div class="tt_descr">' . htmlspecialchars($this->description) . '</div>';
        }
        if ($settings->get('show_strict', 0)) {
            ?>
            <div class="tt-strong-search-box">
                <input type="checkbox" <?php if ($is_strong) echo 'checked="true" '; ?> name="tt_strong" id="tt_strong"
                       onclick="document.ttSearchForm.submit()"/> <label
                    for="tt_strong"><?php echo JText::_('COM_TORTAGS_STRICT_SEARCH'); ?></label>
            </div>
        <?php
        }
        ?>

        <div class="tt-search tt-hide" id="tt-search">
            <span class="tt-icon-search"><!--X--></span>
            <input type="text" class="tt-tag-input" name="tag" value="<?php echo $this->tag_enc; ?>" size="50"
                   maxlength="100"/>
        </div>

        <?php
        if (sizeof($this->results)) {
            foreach ($this->results as $result) {
                $i++;
                $n = ($this->pagination->limitstart + $i);
                echo TTHelper::showResultBox($result, $n);
            }
        } else {
            echo JText::_('COM_TORTAGS_ELEMENTS_NOT_FOUND');
        }
        ?>
    </div>

    <div class="pagination">
        <?php
        if ($deltaginurl) {
            $this->pagination->setAdditionalUrlParam('view', '');
        }
        echo $this->pagination->getPagesLinks();
        ?>
    </div>
    <input type="hidden" value="com_tortags" name="option"/>
    <?php if (!$deltaginurl) { ?><input type="hidden" value="tag" name="view"/><?php } ?>
</form>