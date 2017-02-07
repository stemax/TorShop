<?php
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search_cat'));
?>
            <form action="<?php echo JRoute::_('index.php?option=com_torshop&view=cats'); ?>" method="post"
                  id="adminForm" name="adminForm">
                <div id="j-sidebar-container" class="span2">
                    <?php echo $this->sidebar; ?>
                </div>
                <div id="j-main-container" class="span10">

                    <table class="admin table table-striped" width="100%">
                        <tbody>
                        <tr>
                            <td valign="top" width="100%">
                    <div id="filter-bar" class="btn-toolbar">
                        <div class="filter-search btn-group pull-left">
                            <label for="filter_search_cat"
                                   class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                            <input type="text" name="filter_search_cat" id="filter_search_cat"
                                   placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"
                                   value="<?php echo $this->escape($this->state->get('filter.search_cat')); ?>"
                                   title="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"/>
                        </div>
                        <div class="btn-group pull-left">
                            <button class="btn hasTooltip" type="submit"
                                    title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i>
                            </button>
                            <button class="btn hasTooltip" type="button"
                                    title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search_cat').value = '';
                                        this.form.submit();"><i class="icon-remove"></i></button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <table class="adminlist table table-striped">
                        <thead>
                        <tr>
                            <th width="1%">
                                <input type="checkbox" name="checkall-toggle" value=""
                                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                                       onclick="Joomla.checkAll(this)"/>
                            </th>
                            <th>
                                <?php echo JHtml::_('grid.sort', 'COM_TORSHOP_TITLE', '`c`.`title`', $listDirn, $listOrder); ?>
                            </th>
                            <th width="5%">
                                <?php echo JHtml::_('grid.sort', 'JSTATUS', '`c`.`published`', $listDirn, $listOrder); ?>
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
                        <?php
                        if ($this->cats) {
                            foreach ($this->cats as $i => $item) {
                                ?>
                                <tr class="row<?php echo $i % 2; ?>">
                                    <td class="center">
                                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                    </td>
                                    <td>
                                        <img
                                            src="<?php echo JURI::root(); ?>administrator/components/com_torshop/assets/images/cats.png"/>
                                        <?php if ($this->user->authorise('core.edit') or ($this->user->authorise('core.edit.own'))) { ?>
                                            <a href="<?php echo JRoute::_('index.php?option=com_torshop&task=cat.edit&id=' . $item->id); ?>">
                                                <?php echo $item->title; ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php echo $item->title;
                                            ?>
                                        <?php } ?>
                                    </td>
                                    <td class="center">
                                        <?php echo JHtml::_('jgrid.published', $item->published, $i, 'cats.', true, 'cb'); ?>
                                    </td>
                                    <td>
                                        <?php echo $item->id; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5" align="center">
                                    <?php echo JText::_('COM_TORSHOP_NOT_FOUND'); ?>
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
                </div>

                </td>
                </tr>
                </tbody>
                </table>
            </form>
