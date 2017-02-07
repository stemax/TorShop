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
if (TT_JVER == 3) {
    ?>
    <style type="text/css">
        #import-export-tab.tabs {
            width: 100%;
            display: inline-block;
        }

        .open {
            background: none repeat scroll 0 0 #EDEDED !important;
        }

        #import-export-tab.tabs dt {
            border: 1px solid #C4C3C3;
            cursor: pointer;
            display: inline-block;
            float: left;
            margin: 4px;
            padding: 4px;
        }

        .adminformlist {
            list-style: none outside none !important;
        }

        .adminformlist label {
            display: inline-block;
            width: 160px;
        }

        .adminformlist li {
            display: block;
            width: 100%;
            clear: both;
            float: none;
        }

        #import-export-tab h3 {
            font-size: 12px !important;
        }
    </style><?php }
?>
<table class="admin table table-striped" width="100%">
    <tbody>
    <tr>
        <td valign="top" width="100%">
            <?php echo JHtml::_('tabs.start', 'import-export-tab', array('useCookie' => 1)); ?>
            <?php if (TT_JVER == 3) { ?>
                <div class="clr"></div>
                <?php echo JHtml::_('tabs.panel', 'Import/Export Joomla3 tags', 'jxlabels-impexp'); ?>
                <div class="clr"></div>
                <br/>
                <div>
                    <div class="<?php
                    if (TT_JVER != 3) {
                        echo 'width-50';
                    }
                    ?> fltlft">
                        <fieldset class="settingfieldset">
                            <legend><?php echo JText::_('COM_TORTAGS_IMPORT_TORTAGS'); ?></legend>
                            <table cellspacing="1" class="adminlist">
                                <tr class="row0">
                                    <div style="float:left;padding:5px">Import tags from:</div>
                                    <div class="button2-left">
                                        <div class="blank">
                                            <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                                               href="index.php?option=com_tortags&amp;task=impexp.importnativetags&amp;tmpl=component">
                                                <?php echo JText::_('Joomla3 Tags (only com_content.article)'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="<?php
                    if (TT_JVER != 3) {
                        echo 'width-50';
                    }
                    ?>  fltlft">
                        <fieldset class="settingfieldset">
                            <legend><?php echo JText::_('COM_TORTAGS_EXPORT_TORTAGS'); ?></legend>
                            <table cellspacing="1" class="adminlist">
                                <tr class="row0">
                                    <div style="float:left;padding:5px">Export tags to:</div>
                                    <div class="button2-left">
                                        <div class="blank">
                                            <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                                               href="index.php?option=com_tortags&amp;task=impexp.exportnativetags&amp;tmpl=component">
                                                <?php echo JText::_('Joomla3 Tags (only com_content.article)'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="clr"></div>
            <?php } ?>

            <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_IMPEXP_TORTAGS'), 'global-impexp'); ?>
            <div class="clr"></div>
            <br/>

            <div class="<?php
            if (TT_JVER != 3) {
                echo 'width-50';
            }
            ?> fltlft">
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_IMPORT_TORTAGS'); ?></legend>
                    <table cellspacing="1" class="adminlist">
                        <tr class="row0">
                            <div style="float:left;padding:5px">Import tags from:</div>
                            <div class="button2-left">
                                <div class="blank">
                                    <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                                       href="index.php?option=com_tortags&amp;task=impexp.importmeta&amp;tmpl=component">
                                        <?php echo JText::_('Meta keywords (only com_content)'); ?>
                                    </a>
                                </div>
                            </div>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="<?php
            if (TT_JVER != 3) {
                echo 'width-50';
            }
            ?>  fltlft">
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_EXPORT_TORTAGS'); ?></legend>
                    <table cellspacing="1" class="adminlist">
                        <tr class="row0">
                            <div style="float:left;padding:5px">Export tags to:</div>
                            <div class="button2-left">
                                <div class="blank">
                                    <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                                       href="index.php?option=com_tortags&amp;task=impexp.exportmeta&amp;tmpl=component">
                                        <?php echo JText::_('Meta keywords (only com_content)'); ?>
                                    </a>
                                </div>
                            </div>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="clr"></div>
            <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_IMPEXP_VIRTUEMART'), 'virtuemart-impexp'); ?>
            <div class="clr"></div>
            <br/>

            <div>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_IMPORT_VIRTUEMART'); ?></legend>
                    <table cellspacing="1" class="adminlist">
                        <tr class="row0">
                            <?php
                            if (file_exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart')) {
                                ?>
                                <div class="tt_succ_import">VirtueMart is installed on your site:</div>
                                <div class="button2-left">
                                    <div class="blank">
                                        <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                                           href="index.php?option=com_tortags&amp;task=impexp.sinchronize&amp;&comp=virtuemart&tmpl=component">
                                            <?php echo JText::_('Import keywords from VirtueMart'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php
                            } else {
                                ?>
                                <div class="tt_error_import">VirtueMart not installed on your site.</div>
                            <?php
                            }
                            ?>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="clr"></div>
            <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_IMPEXP_JXLABELS'), 'jxlabels-impexp'); ?>
            <div class="clr"></div>
            <br/>

            <div>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_IMPORT_JXLABELS'); ?></legend>
                    <table cellspacing="1" class="adminlist">
                        <tr class="row0">
                            <div style="color:red;"><?php echo JText::_('COM_TORTAGS_IMPEXP_JXLABELS_NOTE'); ?></div>
                            <h4><?php echo JText::_('COM_TORTAGS_IMPEXP_JXLABELS_STEPONE'); ?></h4>
                            <?php
                            $db = JFactory::getDBO();

                            $query = "SHOW TABLES LIKE '%jxlabels_labels%'";
                            $db->setQuery($query);
                            $jxlabels_labels = $db->loadResult();
                            if ($jxlabels_labels)
                                echo '<div class="tt_succ_import">' . $jxlabels_labels . '</div>';
                            else
                                echo '<div class="tt_error_import"> jxlabels_labels ' . JText::_('COM_TORTAGS_MIGRATION_TNF') . '</div>';

                            $query = "SHOW TABLES LIKE '%jxlabels_maps%'";
                            $db->setQuery($query);
                            $jxlabels_maps = $db->loadResult();
                            if ($jxlabels_maps)
                                echo '<div class="tt_succ_import">' . $jxlabels_maps . '</div>';
                            else
                                echo '<div class="tt_error_import"> jxlabels_maps ' . JText::_('COM_TORTAGS_MIGRATION_TNF') . '</div>';

                            $query = "SHOW TABLES LIKE '%jxlabels_types%'";
                            $db->setQuery($query);
                            $jxlabels_types = $db->loadResult();
                            if ($jxlabels_types)
                                echo '<div class="tt_succ_import">' . $jxlabels_types . '</div>';
                            else
                                echo '<div class="tt_error_import"> jxlabels_types ' . JText::_('COM_TORTAGS_MIGRATION_TNF') . '</div>';

                            if ($jxlabels_labels && $jxlabels_maps && $jxlabels_types) {
                                ?>
                                <br/>
                                <div class="button2-left">
                                    <div class="blank">
                                        <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                                           href="index.php?option=com_tortags&amp;task=impexp.jxlabelsmigration&amp;tmpl=component">
                                            <?php echo JText::_('COM_TORTAGS_MIGRATION_START'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="clr"></div>
            <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_IMPEXP_JOOMLATAG'), 'jt-impexp'); ?>
            <div class="clr"></div>
            <br/>

            <div>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_IMPEXP_JOOMLATAG'); ?></legend>
                    <table cellspacing="1" class="adminlist">
                        <tr class="row0">
                            <div style="color:red;"><?php echo JText::_('COM_TORTAGS_IMPEXP_JOOMLATAG_NOTE'); ?></div>
                            <h4><?php echo JText::_('COM_TORTAGS_IMPEXP_JOOMLATAG_STEPONE'); ?></h4>
                            <?php
                            $db = JFactory::getDBO();


                            $query = "SHOW TABLES LIKE '%tag_term'";
                            $db->setQuery($query);
                            $tag_term = $db->loadResult();
                            if ($tag_term)
                                echo '<div class="tt_succ_import">' . $tag_term . '</div>';
                            else
                                echo '<div class="tt_error_import"> tag_term ' . JText::_('COM_TORTAGS_MIGRATION_TNF') . '</div>';

                            $query = "SHOW TABLES LIKE '%tag_term_content%'";
                            $db->setQuery($query);
                            $tag_term_content = $db->loadResult();
                            if ($tag_term_content)
                                echo '<div class="tt_succ_import">' . $tag_term_content . '</div>';
                            else
                                echo '<div class="tt_error_import"> tag_term_content ' . JText::_('COM_TORTAGS_MIGRATION_TNF') . '</div>';

                            if ($tag_term_content && $tag_term) {
                                ?>
                                <br/>
                                <div class="button2-left">
                                    <div class="blank">
                                        <a class="modal" rel="{handler: 'iframe'}"
                                           href="index.php?option=com_tortags&amp;task=impexp.jtmigration&amp;tmpl=component">
                                            <?php echo JText::_('COM_TORTAGS_MIGRATION_START'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="clr"></div>
            <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_IMPEXP_EASYBLOG'), 'eb-impexp'); ?>
            <div class="clr"></div>
            <br/>

            <div>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_IMPEXP_EASYBLOG'); ?></legend>
                    <table cellspacing="1" class="adminlist">
                        <tr class="row0">
                            <?php
                            if (file_exists(JPATH_ADMINISTRATOR . '/components/com_easyblog')) {
                                ?>
                                <div class="tt_succ_import">EasyBlog installed on your site:</div>
                                <div class="button2-left">
                                    <div class="blank">
                                        <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                                           href="index.php?option=com_tortags&amp;task=impexp.sinchronize&amp;&comp=easyblog&tmpl=component">
                                            <?php echo JText::_('Start synchronization with EasyBlog tags'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php
                            } else {
                                ?>
                                <div class="tt_error_import">EasyBlog not installed on your site.</div>
                            <?php
                            }
                            ?>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="clr"></div>
            <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_IMPEXP_EASYDISCUS'), 'ed-impexp'); ?>
            <div class="clr"></div>
            <br/>

            <div>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_IMPEXP_EASYDISCUS'); ?></legend>
                    <table cellspacing="1" class="adminlist">
                        <tr class="row0">
                            <?php
                            if (file_exists(JPATH_ADMINISTRATOR . '/components/com_easydiscuss')) {
                                ?>
                                <div class="tt_succ_import">Easydiscuss installed on your site:</div>
                                <div class="button2-left">
                                    <div class="blank">
                                        <a class="modal btn btn-small" rel="{handler: 'iframe'}"
                                           href="index.php?option=com_tortags&amp;task=impexp.sinchronize&amp;&comp=easydiscuss&tmpl=component">
                                            <?php echo JText::_('Start synchronization with EasyDiscuss tags'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php
                            } else {
                                ?>
                                <div class="tt_error_import">Easydiscuss not installed on your site.</div>
                            <?php
                            }
                            ?>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="clr"></div>
            <?php echo JHtml::_('tabs.end'); ?>
        </td>
    </tr>
    </tbody>
</table>