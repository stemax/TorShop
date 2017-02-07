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
return false;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search_tag'));
?>
<table class="admin" width="100%">
    <tbody>
    <tr>
        <td valign="top" width="100%">
            <form action="<?php echo JRoute::_('index.php?option=com_tortags&view=tags'); ?>" method="post"
                  name="adminForm">
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
                        <button type="button" onclick="document.id('filter_search_tag').value = '';
                                                                this.form.submit();">
                            <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
                        </button>
                    </div>
                </fieldset>
                <table class="adminlist">
                    <thead>
                    <tr>
                        <th width="1%">
                            <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)"/>
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
                    <?php if ($this->tags) {
                        foreach ($this->tags as $i => $item) {
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="center">
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td>
                                    <?php if ($this->user->authorize('core.edit') or ($this->user->authorize('core.edit.own'))) { ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_tortags&task=tag.edit&id=' . $item->id); ?>">
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
                                            <div class="blank">
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
