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
JHtml::_('behavior.modal');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search'));
?>
<table class="admin table table-striped" width="100%">
    <tbody>
    <tr>
        <td valign="top" width="100%">
            <form action="index.php?option=com_tortags&view=components" method="post" name="adminForm" id="adminForm">
                <?php if (TT_JVER != 3) { ?>
                    <fieldset id="filter-bar">
                        <div class="filter-search fltlft">
                            <label class="filter-search-lbl" for="filter_search">
                                <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
                            </label>
                            <input type="text" name="filter_search" id="filter_search" value="<?php echo $search; ?>"
                                   title=""/>
                            <button type="submit">
                                <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
                            </button>
                            <button type="button" onclick="document.id('filter_search').value = '';
                                                                    this.form.submit();">
                                <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
                            </button>
                        </div>
                    </fieldset>
                <?php } else { ?>
                    <div id="filter-bar" class="btn-toolbar">
                        <div class="filter-search btn-group pull-left">
                            <label for="filter_search"
                                   class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                            <input type="text" name="filter_search" id="filter_search"
                                   placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"
                                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                                   title="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"/>
                        </div>
                        <div class="btn-group pull-left">
                            <button class="btn hasTooltip" type="submit"
                                    title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i>
                            </button>
                            <button class="btn hasTooltip" type="button"
                                    title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value = '';
                                                                    this.form.submit();"><i class="icon-remove"></i>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
                <table class="adminlist">
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
                            <?php echo JHtml::_('grid.sort', 'COM_TORTAGS_COMPONENT', '`c`.`component`', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%" class="nowrap">
                            <?php echo JHtml::_('grid.sort', 'COM_TORTAGS_COUNT_TAGS', 'tags_count', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%" class="nowrap">
                            <?php echo JHtml::_('grid.sort', 'COM_TORTAGS_WHERE_SHOW', 'c.where_enable', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" class="nowrap">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', '`c`.`id`', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td colspan="9"><?php echo ($this->pagination) ? $this->pagination->getListFooter() : ''; ?></td>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php if ($this->components) {
                        foreach ($this->components as $i => $item) {
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="center">
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td>
                                    <img
                                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/components.png"/>
                                    <?php
                                    $title = ucfirst(str_replace('com_', '', $this->escape($item->component)));
                                    ?>
                                    <?php if ($this->user->authorise('core.edit') or ($this->user->authorise('core.edit.own'))) { ?>
                                        <a href="index.php?option=com_tortags&task=component.edit&id=<?php echo $item->id; ?>">
                                            <?php echo $title; ?>
                                        </a>
                                    <?php } else { ?>
                                        <?php echo $title; ?>
                                    <?php } ?>
                                </td>
                                <td align="center">
                                    <div class="tt_tablcenter">
                                        <div class="button2-left">
                                            <div class="blank">
                                                <a class="btn btn-small"
                                                   href="index.php?option=com_tortags&view=tagslists&filter_cid=<?php echo $item->id; ?>">
                                                    <img
                                                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/tags.png"/><?php echo $item->tags_count; ?>
                                                </a>&nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td align="center">
                                    <div class="tt_tablcenter">
                                        <div class="button2-left">
                                            <div class="blank btn btn-small">
                                                <?php
                                                switch ($item->where_enable) {
                                                    case 0:
                                                        echo '&nbsp;BE FE&nbsp;';
                                                        break;
                                                    case 1:
                                                        echo '&nbsp;BE&nbsp;';
                                                        break;
                                                    case 2:
                                                        echo '&nbsp;FE&nbsp;';
                                                        break;
                                                }
                                                ?>
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
                            <td colspan="5" align="center">
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
