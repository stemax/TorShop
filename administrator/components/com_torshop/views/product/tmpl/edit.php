<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$item = $this->item;
?>
<div class="form-horizontal">

<table class="admin  table table-striped " width="100%">
    <tbody>
    <td valign="top" width="100%">
        <form
            action="<?php echo JRoute::_('index.php?option=com_torshop&view=product&layout=edit&id=' . (int)$item->id); ?>"
            method="post" name="adminForm" id="adminForm" class="form-validate">
            <fieldset class="adminform">
                <ul class="adminformlist">
                    <?php
                    echo '<li>';
                    echo $this->form->getLabel('title');
                    echo $this->form->getInput('title');

                    echo '</li><li>';
                    echo $this->form->getLabel('alias');
                    echo $this->form->getInput('alias');
                    echo '</li>';

                    echo '<li>';
                    echo $this->form->getLabel('image');
                    echo $this->form->getInput('image');

                    echo '</li><li>';

                    echo '</li><li>';
                    echo $this->form->getLabel('published');
                    echo $this->form->getInput('published');
                    echo '</li>';

                    echo '</li><li>';
                    echo '<br class="clr" />';
                    echo $this->form->getLabel('description');
                    echo '<br class="clr" />';
                    echo $this->form->getInput('description');
                    echo '<br class="clr" />';
                    echo '</li>';
                    ?>
                </ul>
                <br class="clr"/>
            </fieldset>
            <div>
                <input type="hidden" name="task" value="item.edit"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </td>
    </tr>
    </tbody>
</table>
</div>