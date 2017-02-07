<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die;

if (JFactory::getApplication()->isSite()) {
    JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));
}
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$elid = JRequest::getInt('eid');
$oid = JRequest::getInt('oid');
$isnew = JRequest::getInt('isnew');
$function = JRequest::getCmd('function', 'jSelectTags');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search_tag'));

$img_add = JURI::root() . 'components/com_tortags/assets/images/tag_add.png';
?>
<script type="text/javascript">
    var tt_global_tids = new Array();
    if (window.jQuery) {
        jQuery.noConflict();
    }
    function tt_checked(tid, ch) {
        if (ch)
            tt_global_tids.push(tid);
        else {
            for (var i = 0; i < tt_global_tids.length; i++) {
                if (tt_global_tids[i] == tid) {
                    tt_global_tids.splice(tt_global_tids.indexOf(tid), 1);
                }
            }
        }
        document.getElementById('tids').value = tt_global_tids;
    }

    function tt_add_sell_tags() {
        var tt_tags = document.getElementById('tids').value;
        if (tt_tags) {
            if (window.parent)
                if (window.parent.add_selected_tags(tt_tags, <?php echo $isnew; ?>)) {
                    tt_checkAll(false);
                    tt_setdisable(tt_tags);
                    //alert('<?php echo JText::_('COM_TORTAGS_ADD_SELECTED_TAGS_SUCCESS'); ?>');
                    document.getElementById('tt_checkall').checked = false;
                }
        }
    }

    function add_selected_tags(tt_tags) {
        if (!tt_tags) {
            return;
        }
        if (window.parent)
            return window.parent.add_selected_tags(tt_tags);
    }

    function tt_checkAll(ischeck) {
        var tt_form = document.getElementById("adminForm");
        for (var el = 0, n = tt_form.elements.length; el < n; el++) {
            var tt_e = tt_form.elements[el];
            if (tt_e.type == "checkbox") {
                if (tt_e.disabled == false) {
                    if (tt_e.value) {
                        tt_e.checked = ischeck;
                        tt_checked(tt_e.value, ischeck);
                    }
                }
            }
        }
    }

    function tt_setdisable(tt_tags) {
        var tids = tt_tags.split(',');
        if (tids) {

            var tt_form = document.getElementById("adminForm");
            for (var el = 0, n = tt_form.elements.length; el < n; el++) {
                var tt_e = tt_form.elements[el];
                if (tt_e.type == "checkbox") {
                    if (tt_e.disabled == false) {
                        for (var key in tids) {
                            if (tt_e.value == tids[key]) {
                                if (tt_e.value) {
                                    tt_e.checked = true;
                                    tt_e.disabled = true;
                                }
                            }
                        }
                    }
                }
            }
        }

    }
</script>
<form
    action="<?php echo JRoute::_('index.php?option=com_tortags&view=tags&layout=modal&tmpl=component&function=' . $function . '&' . JSession::getFormToken() . '=1'); ?>"
    method="post" name="adminForm" id="adminForm">
    <?php if (TT_JVER != 3) { ?>
        <fieldset class="filter clearfix">
            <div class="left">
                <div class="filter-search fltlft">
                    <label class="filter-search-lbl" for="filter_search_tag">
                        <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
                    </label>
                    <input type="text" name="filter_search_tag" id="filter_search_tag" value="<?php echo $search; ?>"
                           title=""/>
                    <button type="submit">
                        <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
                    </button>
                    <button type="button" onclick="document.id('filter_search_tag').value = '';
            this.form.submit();">
                        <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
                    </button>
                </div>
            </div>
        </fieldset>
    <?php } else { ?>
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search_tag"
                       class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                <input type="text" name="filter_search_tag" id="filter_search_tag"
                       placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"
                       value="<?php echo $this->escape($this->state->get('filter.search_tag')); ?>"
                       title="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"/>
            </div>
            <div class="btn-group pull-left">
                <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i
                        class="icon-search"></i></button>
                <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"
                        onclick="document.id('filter_search_tag').value = '';
            this.form.submit();"><i class="icon-remove"></i></button>
            </div>
        </div>
        <div class="clearfix"></div>
    <?php } ?>
    <div>
        <span class="tt_add_sel_tags" onclick="tt_add_sell_tags();"><img
                src="<?php echo $img_add; ?>"/>  &nbsp;<?php echo JText::_('COM_TORTAGS_ADD_SELECTED_TAGS'); ?></span>
    </div>
    <table class="adminlist  table table-striped">
        <thead>
        <tr>
            <th width="1%">
                <input type="checkbox" id="tt_checkall" name="checkall-toggle" value=""
                       onclick="tt_checkAll(this.checked)"/>
            </th>
            <th>
                <?php echo JHtml::_('grid.sort', 'COM_TORTAGS_TAG', '`t`.`title`', $listDirn, $listOrder); ?>
            </th>
            <th width="12%">
                <?php echo JHtml::_('grid.sort', 'COM_TORTAGS_HITS', '`t`.`hits`', $listDirn, $listOrder); ?>
            </th>
            <th width="1%" class="nowrap">
                <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', '`t`.`id`', $listDirn, $listOrder); ?>
            </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="9"><?php echo ($this->pagination) ? $this->pagination->getListFooter() : ''; ?></td>
        </tr>
        </tfoot>
        <tbody>
        <?php
        $seltags = (array)$this->seltags;

        if ($this->tags) {
            foreach ($this->tags as $i => $item) {
                $select_tag_link = 'index.php?option=com_tortags&amp;view=tags&amp;layout=modal&amp;tmpl=component&amp;eid=' . $elid . '&amp;oid=' . $oid . '&amp;tt_tags=1&amp;' . JSession::getFormToken() . '=1';
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php
                        if (in_array($item->id, $seltags)) {
                            ?>
                            <input type="checkbox" title="" readonly="true" disabled="true" checked="true"
                                   value="<?php echo $item->id; ?>" name="cid[]" id="cb_<?php echo $item->id; ?>"/>
                        <?php
                        } else {
                            ?>
                            <input type="checkbox" title="Checkbox for tag <?php echo $item->id; ?>"
                                   onclick="tt_checked(<?php echo $item->id; ?>, this.checked);"
                                   value="<?php echo $item->id; ?>" name="cid[]" id="cb_<?php echo $item->id; ?>"/>
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $titl = $item->title;
                        echo JHTML::tooltip($item->description, $titl, '', $titl);
                        ?>
                    </td>
                    <td align="center">
                        <div class="tt_tablcenter">
                            <div class="button2-left">
                                <div class="blank btn btn-small">
                                    &nbsp;<img
                                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/hits.png"/>
                                    <?php echo $item->hits; ?>&nbsp;
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="tt_tablcenter">
                            <div class="button2-left">
                                <div class="blank btn btn-small">
                                    &nbsp;<?php echo $item->id; ?>&nbsp;
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4" align="center">
                    <?php echo JText::_('COM_TORTAGS_NOT_FOUND'); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" id="tids" name="tids" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="eid" value="<?php echo $elid; ?>"/>
        <input type="hidden" name="oid" value="<?php echo $oid; ?>"/>
        <input type="hidden" name="isnew" value="<?php echo $isnew; ?>"/>
        <input type="hidden" name="tt_tags" value="<?php echo JRequest::getInt('tt_tags'); ?>"/>
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
