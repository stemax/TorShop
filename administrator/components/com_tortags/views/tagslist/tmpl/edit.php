<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$item = $this->item;
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'component.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
    function getTables(comp) {
        if (!comp)
            return;
        var url = '<?php echo 'index.php?option=com_tortags&task=gettables'; ?>';
        var a = new Request({
            url: url,
            data: 'table=' + comp,
            method: 'post',
            onRequest: function () {
                $('tables').set('html', '<label>loading...</label>');
            },
            onSuccess: function (responseText) {
                $('tables').set('html', responseText);
                if ($('edit_descr'))
                    $('edit_descr').set('html', '');
                if ($('edit_cr'))
                    $('edit_cr').set('html', '');
            },
            onFailure: function () {
                $('tables').set('html', '<label>Sorry, your request failed :(</label>');
            }
        }).send();
    }
</script>
<table class="admin" width="100%">
    <tbody>
    <td valign="top" width="100%">
        <form
            action="<?php echo JRoute::_('index.php?option=com_tortags&view=component&layout=edit&id=' . (int)$item->id); ?>"
            method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">
            <?php
            echo JHtml::_('tabs.start', 'item-tabs');
            foreach ($this->form->getFieldsets() as $fieldset) {
                $fields = $this->form->getFieldset($fieldset->name);
                if (count($fields) > 0) {
                    echo JHtml::_('tabs.panel', JText::_($fieldset->label), 'item-' . $fieldset->name);
                    ?>
                    <fieldset class="adminform">
                        <ul class="adminformlist">

                            <?php
                            echo '<li>';
                            echo $this->form->getLabel('component');
                            echo $this->form->getInput('component');

                            echo '</li><li>';
                            echo $this->form->getLabel('table');
                            echo $this->form->getInput('table');

                            if ($item->table) {
                                echo $this->getItemsByTable($item);
                            }

                            echo '</li><li>';
                            echo $this->form->getLabel('url_template');
                            echo $this->form->getInput('url_template');
                            echo '</li>';
                            echo '</li><li>';
                            echo $this->form->getLabel('exc_views');
                            echo $this->form->getInput('exc_views');
                            echo '</li><li>';
                            echo $this->form->getLabel('where_enable');
                            echo $this->form->getInput('where_enable');
                            echo '</li>';
                            ?>
                        </ul>
                        <br class="clr"/>
                    </fieldset>
                <?php
                }
            }
            $tags = $this->tags;
            if (sizeof($tags)) {
                echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_MANAGE_ATACHEDTAGS'), 'item-atached-tags');
                ?>
                <script type="text/javascript">
                    function tt_delete_link(id) {
                        if (confirm('<?php echo JText::_('COM_TORTAGS_AREYOUSUREWANTDELETE'); ?>'))
                            if (id) {
                                var url = "<?php echo JURI::root() ?>administrator/index.php?option=com_tortags&task=deletelink&tmpl=component";
                                var dan = "id=" + id;
                                var statusdiv = $('m_' + id);
                                var myAjax = new Request.HTML({
                                    url: url,
                                    method: "post",
                                    data: dan,
                                    encoding: "utf-8",
                                    update: statusdiv

                                });
                                myAjax.send();
                            }
                    }
                </script>

                <table class="adminlist" width="80%">
                    <thead>
                    <tr>
                        <th width="3%">
                            <?php echo JText::_('COM_TORTAGS_TABLE_TID'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_TORTAGS_TAG'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_TORTAGS_ELEMENT'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_TORTAGS_ELEMENT_ID'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_TORTAGS_DELETE_CONNECTION'); ?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($tags as $i => $tag) {
                        ?>
                        <tr id="m_<?php echo $tag->id; ?>" class="row<?php echo $i % 2; ?>">
                            <td align="center">
                                <div class="tt_tablcenter">
                                    <div class="button2-left">
                                        <div class="blank">
                                            &nbsp;<?php echo $tag->tid; ?>&nbsp;
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <img
                                    src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/tags.png"/>
                                <?php echo $tag->tag; ?>
                            </td>
                            <td><?php echo $tag->artitle; ?></td>
                            <td align="center" width="3%">
                                <div class="tt_tablcenter">
                                    <div class="button2-left">
                                        <div class="blank">
                                            &nbsp;<?php echo $tag->item_id; ?>&nbsp;
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td align="center">
                                <div class="tt_tablcenter">
                                    <div class="button2-left">
                                        <div class="blank">
                                            <a href="javascript:void(0)"
                                               onclick="tt_delete_link(<?php echo $tag->id; ?>)"><img
                                                    src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/link_delete.png"/></a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
                <br class="clr"/>
            <?php
            }


            echo JHtml::_('tabs.end');
            ?>
            <div>
                <input type="hidden" name="task" value="item.edit"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </td>
    </tr>
    </tbody>
</table>

