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

$lang = JFactory::getLanguage();
$lang->load('com_tortags', JPATH_ADMINISTRATOR);

$search = $this->escape($this->state->get('filter.search_tag'));

$elid = JRequest::getInt('eid');
$oid = JRequest::getInt('oid');
$isnew = JRequest::getInt('isnew');
$cid = JRequest::getInt('cid');

$img_add = JURI::root() . 'components/com_tortags/assets/images/tag_add.png';
?>
<script type="text/javascript">
    var tt_global_tids = new Array();
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
                    //alert('Succesfully');
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
    action="<?php echo JRoute::_('index.php?option=com_tortags&view=tags&layout=modal&tmpl=component&' . JSession::getFormToken() . '=1'); ?>"
    method="post" name="adminForm" id="adminForm">
    <fieldset class="filter clearfix">
        <div>
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
    <div>
        <span class="tt_add_sel_tags" onclick="tt_add_sell_tags();"><img
                src="<?php echo $img_add; ?>"/>  &nbsp;<?php echo JText::_('COM_TORTAGS_ADD_SELECTED_TAGS'); ?></span>
    </div>
    <table class="adminlist tt_adminlist">
        <thead>
        <tr>
            <th width="1%">
                <input type="checkbox" id="tt_checkall" name="checkall-toggle" value=""
                       onclick="tt_checkAll(this.checked)"/>
            </th>
            <th>
                <?php echo JText::_('COM_TORTAGS_TAG'); ?>
            </th>
            <th width="12%">
                <?php echo JText::_('COM_TORTAGS_HITS'); ?>
            </th>
            <th width="1%" class="nowrap">
                <?php echo JText::_('JGRID_HEADING_ID'); ?>
            </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="9">
                <div class="pagination">
                    <?php echo $this->pagination->getPagesLinks(); ?>
                </div>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <?php
        $seltags = (array)$this->seltags;

        if ($this->tags) {
            foreach ($this->tags as $i => $item) {
                $select_tag_link = 'index.php?option=com_tortags&amp;view=tags&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';
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
                                <div class="blank">
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
                                <div class="blank">
                                    &nbsp;<?php echo $item->id; ?>&nbsp;
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="3" align="center">
                    <?php echo JText::_('COM_TORTAGS_NOT_FOUND'); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="layout" value="modal"/>
        <input type="hidden" name="tmpl" value="component"/>
        <input type="hidden" name="eid" value="<?php echo $elid; ?>"/>
        <input type="hidden" name="oid" value="<?php echo $oid; ?>"/>
        <input type="hidden" name="isnew" value="<?php echo $isnew; ?>"/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" id="tids" name="tids" value=""/>
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>