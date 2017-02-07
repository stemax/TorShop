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
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search_tag'));
$cats = $this->cats;
?>
<script lang="javascript">
    function tt_clear_all() {
        document.id('filter_search_tag').value = '';
        document.id('filter_cid').value = '';
        document.id('filter_search_tag').value = '';
        this.form.submit();
    }
</script>
<table class="admin table table-striped" width="100%">
    <tbody>
    <tr>
        <td valign="top" width="100%">
            <form action="<?php echo JRoute::_('index.php?option=com_tortags&view=tags'); ?>" method="post"
                  name="adminForm" id="adminForm">
                <?php if (TT_JVER != 3) { ?>
                    <fieldset id="filter-bar">
                        <div class="filter-search fltlft">
                            <label class="filter-search-lbl" for="filter_search_tag">
                                <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
                            </label>
                            <input type="text" name="filter_search_tag" id="filter_search_tag"
                                   value="<?php echo $search; ?>" title=""/>
                            <button type="submit">
                                <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
                            </button>
                            <button type="button" onclick="tt_clear_all();">
                                <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
                            </button>
                        </div>
                        <div class="filter-select fltrt">
                            <?php
                            $cat_arr = array();
                            $cat_arr[] = JHTML::_('select.option', JText::_('JOPTION_SELECT_CATS'), 0);

                            if (sizeof($cats)) {
                                foreach ($cats as $cat) {
                                    $cat_arr[] = JHTML::_('select.option', $cat->value, $cat->key);
                                }
                            }
                            echo JHTML::_('select.genericlist', $cat_arr, 'filter_cid', 'class="inputbox" onchange="this.form.submit()"', 'text', 'value', JRequest::getInt('filter_cid'));
                            ?>
                            <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                                <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE'); ?></option>
                                <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language')); ?>
                            </select>
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
                            <button class="btn hasTooltip" type="submit"
                                    title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i>
                            </button>
                            <button class="btn hasTooltip" type="button"
                                    title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search_tag').value = '';
                                        this.form.submit();"><i class="icon-remove"></i></button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
                <table class="adminlist table table-striped">
                    <thead>
                    <tr>
                        <th width="1%">
                            <?php if (TT_JVER != 3) { ?>
                                <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)"/>
                            <?php } else { ?>
                                <input type="checkbox" name="checkall-toggle" value=""
                                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                                       onclick="Joomla.checkAll(this)"/>
                            <?php } ?>
                        </th>
                        <th>
                            <?php echo JHtml::_('grid.sort', 'COM_TORTAGS_TAG', '`t`.`title`', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%">
                            <?php echo JHtml::_('grid.sort', 'COM_TORTAGS_COUNT_IN_COMP', '`comp_count`', $listDirn, $listOrder); ?>
                        </th>
                        <th width="8%">
                            <?php echo JHtml::_('grid.sort', 'COM_TORTAGS_HITS', '`t`.`hits`', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%">
                            <?php echo JHtml::_('grid.sort', 'JSTATUS', '`t`.`published`', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', '`t`.`language`', $listDirn, $listOrder); ?>
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
                    if ($this->tags) {
                        foreach ($this->tags as $i => $item) {
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="center">
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td>
                                    <?php if ($this->user->authorise('core.edit') or ($this->user->authorise('core.edit.own'))) { ?>
                                        <a class="btn btn-small"
                                           href="<?php echo JRoute::_('index.php?option=com_tortags&task=tag.edit&id=' . $item->id); ?>">
                                            <img
                                                src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/tags.png"/><?php echo $this->escape($item->title); ?>
                                        </a>
                                    <?php } else { ?>
                                        <?php echo $this->escape($item->title); ?>
                                    <?php } ?>
                                </td>
                                <td align="center">
                                    <div class="tt_tablcenter">
                                        <div class="button2-left">
                                            <div class="blank btn btn-small">
                                                <img
                                                    src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/components.png"/>&nbsp;<?php echo $item->comp_count; ?>
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
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
                                <td class="center">
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'tags.', true, 'cb'); ?>
                                </td>
                                <td class="center">
                                    <?php if ($item->language == '*'): ?>
                                        <?php echo JText::alt('JALL', 'language'); ?>
                                    <?php else: ?>
                                        <?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
                                    <?php endif; ?>
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
                    } else {
                        ?>
                        <tr>
                            <td colspan="8" align="center">
                                <?php echo JText::_('COM_TORTAGS_NOT_FOUND'); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div>
                    <input type="hidden" name="task" value=""/>
                    <input type="hidden" name="boxchecked" value="0"/>
                    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>
        </td>
    </tr>
    </tbody>
</table>