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
<table class="admin  table table-striped " width="100%">
    <tbody>
    <td valign="top" width="100%">
        <form
            action="<?php echo JRoute::_('index.php?option=com_tortags&view=cat&layout=edit&id=' . (int)$item->id); ?>"
            method="post" name="adminForm" id="adminForm" class="form-validate">
            <fieldset class="adminform">
                <ul class="adminformlist">
                    <?php
                    echo '<li>';
                    echo $this->form->getLabel('catname');
                    echo $this->form->getInput('catname');

                    echo '</li><li>';
                    echo $this->form->getLabel('alias');
                    echo $this->form->getInput('alias');
                    echo '</li>';

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

