<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');

$imgpath = JURI::root() . '/administrator/components/com_tortags/assets/images/';
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
    <table width="70%" border="0" style="float:left" class="table-striped">
        <tbody>
        <tr>
            <td width="15%">
                <img src="<?php echo $imgpath; ?>development.png"/>
            </td>
            <td>
                <h1><?php echo JText::_('COM_TORTAGS'); ?>
                    <img src="<?php echo $imgpath; ?>j16_native.png"/>
                    <img src="<?php echo $imgpath; ?>j17_native.png"/>
                    <img src="<?php echo $imgpath; ?>j25_native.png"/>
                    <img src="<?php echo $imgpath; ?>j30_native.png"/>
                </h1>
                <span class="copyright">[ <?php echo JText::_('COM_TORTAGS_DESCRIPTION'); ?> ]</span>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_TORTAGS_VERSION'); ?>:</td>
            <td>
                <div class="button2-left">
                    <div class="blank btn btn-small">
                        &nbsp;<?php echo TorTagsHelper::getVersion(); ?>&nbsp;
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_TORTAGS_SITE'); ?>:</td>
            <td>
                <div class="button2-left">
                    <div class="blank">
                        <a target="_blank" class="btn btn-small" href="http://tormix.com">http://tormix.com</a>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_TORTAGS_SITE_ARTICLES'); ?>:</td>
            <td>
                <div class="button2-left">
                    <div class="blank">
                        <a target="_blank" class="btn btn-small" href="http://tormix.com/tortags-documentation.html">http://tormix.com/tortags-documentation.html</a>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_TORTAGS_SUPPORT'); ?>:</td>
            <td>
                <div class="button2-left">
                    <div class="blank">
                        <a href="mailto:support@tormix.com" class="btn btn-small">support@tormix.com</a>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_TORTAGS_CHANGELOG'); ?>:</td>
            <td>
                <div class="button2-left">
                    <div class="blank">
                        <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                           href="index.php?option=com_tortags&amp;task=history&amp;tmpl=component">
                            TorTags version history
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <a target="_blank" href="http://extensions.joomla.org/extensions/search-a-indexing/tags-a-clouds/18853"
                   title="">
                    <img src="<?php echo $imgpath; ?>joomla_extensions.png" alt="" border="0"/>
                </a>

            </td>
            <td>
                <div class="button2-left">
                    <div class="blank">
                        <a target="_blank" class="btn btn-small"
                           href="http://extensions.joomla.org/extensions/search-a-indexing/tags-a-clouds/18853/review">Your
                            review is very important to us. Thank you!</a>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="tt_stat tortags-info-txt">
        <strong><?php echo JText::_('COM_TORTAGS_INFRM') ?>:</strong>
        <?php $info = $this->info; ?>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_INST_SYSPLUG'); ?>:</span>
            <?php echo isInstalled($info, 'tt_sys_plgn'); ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_INST_CONTPLUG'); ?>:</span>
            <?php echo isInstalled($info, 'tt_cont_plgn'); ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_INST_BUTPLUG'); ?>:</span>
            <?php echo isInstalled($info, 'tt_but_plgn'); ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_INST_SEARCHPLUG'); ?>:</span>
            <?php echo isInstalled($info, 'tt_search_plgn'); ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_INST_SMARTSEARCHPLUG'); ?>:</span>
            <?php echo isInstalled($info, 'tt_finder_plgn'); ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_INST_CLOUD_MODULE'); ?>:</span>
            <?php echo isInstalled($info, 'tt_module_cloud'); ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_INST_HITS_MODULE'); ?>:</span>
            <?php echo isInstalled($info, 'tt_module_hits'); ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_INST_ELMSBYTAG_MODULE'); ?>:</span>
            <?php echo isInstalled($info, 'tt_module_elms'); ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_COM_COUNT'); ?>:</span>
            <?php echo $info['com_count']; ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_TAGS_COUNT'); ?>:</span>
            <?php echo $info['tags_count'] . ' (<span class="tt_installed">' . $info['tags_public'] . '</span> / <span class="tt_not_installed">' . ($info['tags_count'] - $info['tags_public']) . '</span>)';
            ?>
        </div>
        <div>
            <span class="tt_title_info"><?php echo JText::_('COM_TORTAGS_CATS_COUNT'); ?>:</span>
            <?php echo $info['cats_count']; ?>
        </div>
        <hr/>
        <div>
            <span class="tt_title_info">PHP:</span>
            <?php echo phpversion(); ?>
        </div>
        <div>
            <span class="tt_title_info">MySQL:</span>
            <?php echo JFactory::getDBO()->getVersion(); ?>
        </div>
        <div>
            <span class="tt_title_info">Joomla:</span>
            <?php
            $ver = new JVersion();
            echo $ver->getShortVersion();
            ?>
        </div>
    </div>
    <div class="tortags-info-right tt_coprght tortags-info-txt">
        <img src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/belarus.png"/> Made in
        Belarus [Зроблена ў Беларусі]
    </div>
<?php

function isInstalled($info, $name = '')
{
    $html = '';
    if (isset($info[$name])) {
        $html = '<span class="tt_installed">' . JText::_('COM_TORTAGS_INSTALED') . '</span>';
        if ($info[$name]->enabled) {
            $html .= ' / <span class="tt_installed">' . JText::_('COM_TORTAGS_ENABLED') . '</span>';
        } else
            $html .= ' / <span class="tt_not_installed">' . JText::_('COM_TORTAGS_NOT_ENABLED') . '</span>';
    } else {
        $html = '<span class="tt_not_installed">' . JText::_('COM_TORTAGS_NOT_INSTALED') . '</span>';
    }
    return $html;
}