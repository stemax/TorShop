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
if (TT_JVER == 3) {
    ?>
    <style type="text/css">.adminformlist {
            list-style: none outside none !important;
        }

        .adminformlist label {
            display: inline-block;
            width: 180px;
        }

        .adminformlist li {
            display: block;
            width: 100%;
            clear: both;
            float: none;
        }</style><?php }
?>
<script type="text/javascript">

    Joomla.submitbutton = function (task) {
        if (task == 'component.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task, document.getElementById('adminForm'));
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
                $('tt_tables').set('html', '<label>loading...</label>');
            },
            onSuccess: function (responseText) {
                $('tt_tables').set('html', responseText);
                if ($('edit_descr'))
                    $('edit_descr').set('html', '');
                if ($('edit_cr'))
                    $('edit_cr').set('html', '');
            },
            onFailure: function () {
                $('tt_tables').set('html', '<label>Sorry, your request failed :(</label>');
            }
        }).send();
    }
</script>
<table class="admin table table-striped" width="100%">
    <tbody>
    <td valign="top" width="100%">
        <form
            action="<?php echo JRoute::_('index.php?option=com_tortags&view=component&layout=edit&id=' . (int)$item->id); ?>"
            method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
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
                            echo '</li><li>';
                            echo $this->form->getLabel('def_lang');
                            echo $this->form->getInput('def_lang');
                            echo '</li>';
                            ?>
                        </ul>
                        <br class="clr"/>
                    </fieldset>
                <?php }
            } ?>
            <div>
                <input type="hidden" name="task" value="item.edit"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </td>
    </tr>
    </tbody>
</table>

