<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
if (TT_JVER == 3) {
    ?>
    <style type="text/css">
        #settings-tabs.tabs {
            width: 100%;
            display: inline-block;
        }

        .open {
            background: none repeat scroll 0 0 #EDEDED !important;
        }

        #settings-tabs.tabs dt {
            border: 1px solid #C4C3C3;
            cursor: pointer;
            display: inline-block;
            float: left;
            margin: 4px;
            padding: 4px;
        }

        #settings-tabs.tabs dt h3 {
            font-size: 14px;
            line-height: 8px;
        }

        .adminformlist {
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
        }

        .width-50 {
            width: 48% !important;
            padding: 5px;
        }

        .fltlft {
            float: left;
        }

        .settingfieldset input[type="radio"] {
            float: none !important;
            margin: 0px !important;
        }
    </style><?php
}

$setting_form = $this->form->getFieldset('settings');
foreach ($setting_form as $setting) {
    $fieldname = $setting->fieldname;
    $value = $this->settings->get($fieldname);
    $this->form->setValue($fieldname, null, $value);
}

$setting_form = $this->form->getFieldset('module');
foreach ($setting_form as $setting) {
    $fieldname = $setting->fieldname;
    $value = $this->settings->get($fieldname);
    $this->form->setValue($fieldname, null, $value);
}
if ($this->ut) {
    ?>
    <script type="text/javascript">
        function delUnattachTags() {
            var url = '<?php echo 'index.php?option=com_tortags&task=delunattachtags&tmpl=component'; ?>';
            var a = new Request({
                url: url,
                method: 'post',
                onRequest: function () {
                    $('deltagsres').set('html', '<label>loading...</label>');
                },
                onSuccess: function (responseText) {
                    $('deltagsres').set('html', '<?php echo JText::sprintf('COM_TORTAGS_N_ITEMS_DELETED', $this->ut) ?>');
                },
                onFailure: function () {
                    $('deltagsres').set('html', '<label>Sorry, your request failed :(</label>');
                }
            }).send();
        }
    </script>
<?php }
if ($this->ea) {
    ?>
    <script type="text/javascript">
        function createAliasesForTags() {
            var url = '<?php echo 'index.php?option=com_tortags&task=fixaliases&tmpl=component'; ?>';
            var a = new Request({
                url: url,
                method: 'post',
                onRequest: function () {
                    $('createaliases').set('html', '<label>loading...</label>');
                },
                onSuccess: function (responseText) {
                    $('createaliases').set('html', '<?php echo JText::sprintf('Aliases fixed for %s elements', $this->ea) ?>');
                },
                onFailure: function () {
                    $('createaliases').set('html', '<label>Sorry, your request failed :(</label>');
                }
            }).send();
        }
    </script>
<?php } ?>
<form action="<?php echo 'index.php?option=com_tortags&view=settings&layout=edit&id=' . (int)$this->item->id; ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <table class="admin" width="100%">
        <tr>
            <td valign="top" width="100%">
                <?php echo JHtml::_('tabs.start', 'settings-tabs', array('useCookie' => 1)); ?>
                <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_GENERAL_SETTINGS'), 'general-details'); ?>

                <div class="width-50 fltlft">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_TORTAGS_RESULTPAGE_SETTINGS'); ?></legend>
                        <ul class="adminformlist">
                            <li>
                                <?php echo $this->form->getLabel('result_open_type'); ?>
                                <?php echo $this->form->getInput('result_open_type'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_limit'); ?>
                                <?php echo $this->form->getInput('result_limit'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('search_exact'); ?>
                                <?php echo $this->form->getInput('search_exact'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_use_search_prepare'); ?>
                                <?php echo $this->form->getInput('result_use_search_prepare'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_show_enable_highlighting'); ?>
                                <?php echo $this->form->getInput('result_show_enable_highlighting'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_show_numbers'); ?>
                                <?php echo $this->form->getInput('result_show_numbers'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_date_enabled'); ?>
                                <?php echo $this->form->getInput('result_date_enabled'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_tags_enabled'); ?>
                                <?php echo $this->form->getInput('result_tags_enabled'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_ordering'); ?>
                                <?php echo $this->form->getInput('result_ordering'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_ordering_type'); ?>
                                <?php echo $this->form->getInput('result_ordering_type'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('show_strict'); ?>
                                <?php echo $this->form->getInput('show_strict'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('user_can_change'); ?>
                                <?php echo $this->form->getInput('user_can_change'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_show_readmore'); ?>
                                <?php echo $this->form->getInput('result_show_readmore'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('result_readmore_text'); ?>
                                <?php echo $this->form->getInput('result_readmore_text'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('show_contet_cat_in_results'); ?>
                                <?php echo $this->form->getInput('show_contet_cat_in_results'); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>

                <div class="width-50 fltlft">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_TORTAGS_AUTOCOMPLETER_SETTINGS'); ?></legend>
                        <ul class="adminformlist">
                            <li>
                                <?php echo $this->form->getLabel('autocompleter_enabled'); ?>
                                <?php echo $this->form->getInput('autocompleter_enabled'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('autocompleter_type'); ?>
                                <?php echo $this->form->getInput('autocompleter_type'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('autocompleter_limit'); ?>
                                <?php echo $this->form->getInput('autocompleter_limit'); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>

                <div class="width-50 fltlft">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_TORTAGS_CLEAR_SETTINGS'); ?></legend>
                        <ul class="adminformlist">
                            <li>
                                <?php if ($this->ut) { ?>
                                    <div id="deltagsres" style="float:left;">
                                        <div class="button2-left">
                                            <div class="blank btn btn-small">
                                                <a href="javascript:void(0);" onclick="delUnattachTags();"><img
                                                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/link_delete.png"/> <?php echo JText::_('COM_TORTAGS_CLEAR_BUTTON'); ?>
                                                    : <?php echo $this->ut; ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else echo '<font color="green">' . JText::_('COM_TORTAGS_CLEAR_EMPTY') . '</font>'; ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <div class="width-50 fltlft">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_TORTAGS_MULTILANG_SETTINGS'); ?></legend>
                        <ul class="adminformlist">
                            <li>
                                <?php echo $this->form->getLabel('multilang_mode_enabled'); ?>
                                <?php echo $this->form->getInput('multilang_mode_enabled'); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>

                <div class="width-50 fltlft">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_TORTAGS_TAG_ADDITION'); ?></legend>
                        <ul class="adminformlist">
                            <?php
                            /*
                              <li>
                              <?php echo $this->form->getLabel('autopublish_new'); ?>
                              <?php echo $this->form->getInput('autopublish_new'); ?>
                              </li>
                             */
                            ?>
                            <li>
                                <?php echo $this->form->getLabel('sinchronization_meta'); ?>
                                <?php echo $this->form->getInput('sinchronization_meta'); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <?php /*
                  <div class="width-50 fltlft">
                  <fieldset class="settingfieldset">
                  <legend><?php echo JText::_('COM_TORTAGS_TAG_ALPHABET_LIST'); ?></legend>
                  <ul class="adminformlist">
                  <li>
                  <?php echo $this->form->getLabel('alphabet_list'); ?>
                  <?php echo $this->form->getInput('alphabet_list'); ?>
                  </li>
                  </ul>
                  </fieldset>
                  </div>
                 */ ?>
                <div class="clr"></div>
                <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_STYLE_SETTINGS'), 'style-details'); ?>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_STYLE_SETTINGS'); ?></legend>
                    <ul class="adminformlist">
                        <li>
                            <?php echo $this->form->getLabel('style'); ?>
                            <?php echo $this->form->getInput('style'); ?>
                        </li>
                    </ul>
                </fieldset>
                <fieldset>
                    <legend><?php echo JText::_('COM_TORTAGS_SETTINGS_CSSLIST'); ?></legend>
                    <table border="0">
                        <?php
                        $files = JFolder::files(JPATH_SITE . DS . 'components' . DS . 'com_tortags' . DS . 'assets' . DS . 'css', '.css');
                        if (sizeof($files)) {
                            foreach ($files as $file) {
                                $file_path = JPATH_SITE . '/components/com_tortags/assets/css/' . $file;
                                echo '<tr>';
                                echo '<td>';
                                echo $file;
                                echo '</td>';
                                echo '<td>';
                                ?>
                                <div class="button2-left">
                                    <div class="blank btn btn-small">
                                        <a href="<?php echo str_replace('administrator/', '', JURI::root()); ?>index.php?option=com_tortags&task=prevcss&f=<?php echo str_replace('.css', '', $file); ?>&tmpl=component"
                                           rel="{handler: 'iframe', size: {x: 250, y: 100}}"
                                           class="modal"><?php echo JText::_('COM_TORTAGS_TEMPLATE_PREVIEW_CSS'); ?></a>
                                    </div>
                                </div>
                                <?php
                                echo '</td>';
                                echo '<td>';
                                if (file_exists($file_path) && is_writable($file_path)) {
                                    ?>
                                    <div class="button2-left">
                                        <div class="blank btn btn-small">
                                            <a href="index.php?option=com_tortags&amp;task=settings.editcss&f=<?php echo str_replace('.css', '', $file); ?>&tmpl=component"
                                               rel="{handler: 'iframe', size: {x: 500, y: 400}}"
                                               class="modal"><?php echo JText::_('COM_TORTAGS_TEMPLATE_CSS'); ?></a>
                                        </div>
                                    </div>
                                <?php
                                } else
                                    if (!is_writable($file_path)) {
                                        ?>
                                        <font
                                            color="red"><?php echo JText::_('COM_TORTAGS_TEMPLATE_UNWRITEABLE'); ?></font>
                                    <?php
                                    }
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </fieldset>
                <div class="clr"></div>
                <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_TAGSINELEM_CONFIG'), 'el-tags-config'); ?>
                <div class="width-50 fltlft">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_TORTAGS_TAGSINELEM_CONFIG_GENERAL'); ?></legend>
                        <ul class="adminformlist">
                            <li>
                                <?php echo $this->form->getLabel('tinel_count'); ?>
                                <?php echo $this->form->getInput('tinel_count'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tinel_order'); ?>
                                <?php echo $this->form->getInput('tinel_order'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tooltip_enabled'); ?>
                                <?php echo $this->form->getInput('tooltip_enabled'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tinel_pretext'); ?>
                                <?php echo $this->form->getInput('tinel_pretext'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tinel_showother'); ?>
                                <?php echo $this->form->getInput('tinel_showother'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tinel_showalltext'); ?>
                                <?php echo $this->form->getInput('tinel_showalltext'); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <div class="width-50 fltlft">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_TORTAGS_TAGSINELEM_CONFIG_FORJCONTENT'); ?></legend>
                        <ul class="adminformlist">
                            <li>
                                <?php echo $this->form->getLabel('tinel_enbl_autoinsert'); ?>
                                <?php echo $this->form->getInput('tinel_enbl_autoinsert'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tinel_showonlyinarticle'); ?>
                                <?php echo $this->form->getInput('tinel_showonlyinarticle'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tinel_showin'); ?>
                                <?php echo $this->form->getInput('tinel_showin'); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <div class="width-50 fltlft">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_TORTAGS_TAGSINELEM_CONFIG_FOR_CATS'); ?></legend>
                        <ul class="adminformlist">
                            <li>
                                <?php echo $this->form->getLabel('tinel_cats_show'); ?>
                                <?php echo $this->form->getInput('tinel_cats_show'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tinel_cats_pretext'); ?>
                                <?php echo $this->form->getInput('tinel_cats_pretext'); ?>
                            </li>
                            <li>
                                <?php echo $this->form->getLabel('tinel_cats_link'); ?>
                                <?php echo $this->form->getInput('tinel_cats_link'); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <div class="clr"></div>
                <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_ALLTAGS_CONFIG'), 'all-tags-config'); ?>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_ALL_LIST_SETTINGS'); ?></legend>
                    <ul class="adminformlist">
                        <li>
                            <?php echo $this->form->getLabel('alphalimit'); ?>
                            <?php echo $this->form->getInput('alphalimit'); ?>
                        </li>
                        <li>
                            <?php echo $this->form->getLabel('show_alpha'); ?>
                            <?php echo $this->form->getInput('show_alpha'); ?>
                        </li>
                        <li>
                            <?php echo $this->form->getLabel('alltags_ordering'); ?>
                            <?php echo $this->form->getInput('alltags_ordering'); ?>
                        </li>
                    </ul>
                </fieldset>
                <div class="clr"></div>
                <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_CATEG_CONFIG'), 'categ-tags-config'); ?>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_CATEGORY_SETTINGS'); ?></legend>
                    <ul class="adminformlist">
                        <li>
                            <?php echo $this->form->getLabel('cats_show_alpha'); ?>
                            <?php echo $this->form->getInput('cats_show_alpha'); ?>
                        </li>
                        <li>
                            <?php echo $this->form->getLabel('cats_show_strict'); ?>
                            <?php echo $this->form->getInput('cats_show_strict'); ?>
                        </li>
                        <li>
                            <?php echo $this->form->getLabel('cats_show_count_elements'); ?>
                            <?php echo $this->form->getInput('cats_show_count_elements'); ?>
                        </li>
                        <li>
                            <?php echo $this->form->getLabel('cats_showtags'); ?>
                            <?php echo $this->form->getInput('cats_showtags'); ?>
                        </li>
                        <li>
                            <?php echo $this->form->getLabel('cats_show_elements'); ?>
                            <?php echo $this->form->getInput('cats_show_elements'); ?>
                        </li>
                    </ul>
                </fieldset>
                <div class="clr"></div>
                <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_SEF_CONFIG'), 'sef-config'); ?>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_SETTINGS_SEF'); ?></legend>
                    <?php echo $this->form->getLabel('alias_replace_symbol'); ?>
                    <?php echo $this->form->getInput('alias_replace_symbol'); ?>
                    <?php echo $this->form->getLabel('del_tag_in_url'); ?>
                    <?php echo $this->form->getInput('del_tag_in_url'); ?>
                    <?php echo $this->form->getLabel('meta_order'); ?>
                    <?php echo $this->form->getInput('meta_order'); ?>
                    <div class="clr"></div>
                    <div>
                        <?php if ($this->ea) { ?>
                            <div id="createaliases" style="float:left;">
                                <div class="button2-left">
                                    <div class="blank btn btn-small">
                                        <a href="javascript:void(0);" onclick="createAliasesForTags();"><img
                                                src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/tag_add.png"/> <?php echo JText::_('Generate aliases for '); ?>
                                            : <?php echo $this->ea; ?> empty elements</a>
                                    </div>
                                </div>
                            </div>
                        <?php } else echo '<font color="green">' . JText::_('All tags already have aliases') . '</font>'; ?>
                        <li>
                            <div class="clear clr">&nbsp;</div>
                            <fieldset class="settingfieldset">
                                <legend>Core replace:</legend>
                                <table>
                                    <tr>
                                        <th>Symbol</th>
                                        <th>Replace on</th>
                                    </tr>
                                    <tr>
                                        <td>+</td>
                                        <td>{plus}</td>
                                    </tr>
                                    <tr>
                                        <td>@</td>
                                        <td>{at}</td>
                                    </tr>
                                    <tr>
                                        <td>#</td>
                                        <td>{sharp}</td>
                                    </tr>
                                    <tr>
                                        <td>&</td>
                                        <td>{and}</td>
                                    </tr>
                                    <tr>
                                        <td>?</td>
                                        <td>{question}</td>
                                    </tr>
                                    <tr>
                                        <td>%</td>
                                        <td>{percent}</td>
                                    </tr>
                                    <tr>
                                        <td>:</td>
                                        <td>{colon}</td>
                                    </tr>
                                    <tr>
                                        <td>/</td>
                                        <td>{slash}</td>
                                    </tr>
                                </table>
                            </fieldset>
                            <ul>
                        </li>
                        <li>
                            <?php echo $this->form->getLabel('url_spec_symbols'); ?>
                            <?php echo $this->form->getInput('url_spec_symbols'); ?>
                        </li>
                        <li>
                            <?php echo $this->form->getLabel('url_spec_replacers'); ?>
                            <?php echo $this->form->getInput('url_spec_replacers'); ?>
                        </li>
                        </ul>
                    </div>
                </fieldset>
                <div class="clr"></div>
                <?php echo JHtml::_('tabs.panel', JText::_('COM_TORTAGS_SETTINGS_PERMISSION'), 'permission-details'); ?>
                <fieldset class="settingfieldset">
                    <legend><?php echo JText::_('COM_TORTAGS_SETTINGS_COMPER'); ?></legend>
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules');
                    ?>
                </fieldset>

                <div class="clr"></div>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="option" value="com_tortags"/>
                <?php echo JHtml::_('form.token'); ?>
                </div>
                <div class="clr"></div>
                <?php echo JHtml::_('tabs.end'); ?>
            </td>
        </tr>
    </table>
</form>
