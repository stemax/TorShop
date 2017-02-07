<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
//j3.0
if (!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

class com_tortagsInstallerScript
{

    private $version = '3.2.2';

    public function install($parent)
    {
        ?>
        <div>
            <div class="tortags-info-left">
                <img src="<?php echo JURI::root(); ?>media/com_tortags/images/comp_logo.png"/>
            </div>
            <div class="tortags-info-left tortags-info-txt tortags-info-header">
                <?php echo JText::_('COM_TORTAGS_INSTALL_MESSAGE'); ?>
            </div>
            <div class="tortags-info-right tortags-info-txt">
                <img src="<?php echo JURI::root(); ?>media/com_tortags/images/tormix-16.png"/>
                <?php echo JText::_('COM_TORTAGS_INSTALL_VERSION'); ?> <b><?php echo $this->version; ?></b>
            </div>
        </div>
        <br/>
    <?php
    }

    public function uninstall($parent)
    {

    }

    public function update($parent)
    {
        ?>
        <div>
            <div class="tortags-info-left">
                <img src="<?php echo JURI::root(); ?>media/com_tortags/images/comp_logo.png"/>
            </div>
            <div class="tortags-info-left tortags-info-txt tortags-info-header">
                <?php echo JText::_('COM_TORTAGS_UPDATE_MESSAGE'); ?>
            </div>
            <div class="tortags-info-right tortags-info-txt">
                <img src="<?php echo JURI::root(); ?>media/com_tortags/images/tormix-16.png"/>
                <?php echo JText::_('COM_TORTAGS_INSTALL_VERSION'); ?> <b><?php echo $this->version; ?></b>
            </div>
        </div>
        <br/>
    <?php
    }

    public function preflight($type, $parent)
    {
        ?>
        <style type="text/css">
            .tortags-info, .tortags-info-right, .tortags-info-left {
                font-size: 12px;
                padding: 5px;
            }

            .tortags-info-changelog {
                border-radius: 4px;
                border: 1px solid #DDDDDD;
                font-size: 11px;
                height: 250px;
                overflow: auto;
                background-color: F4F4F4;
            }

            .tortags-info-header {
                font-size: 2em;
                color: #55AA55;
            }

            .tortags-info-txt {
                background-color: #0DA003;
                border: 1px solid #CCCCCC;
                border-radius: 10px;
                color: #fff;
                font-family: Lucida Console;
                margin: 10px;
                text-shadow: 1px 1px 2px #000000;
            }

            .tortags-info-left {
                float: left;
            }

            .tortags-info-right {
                float: right;
            }

            .tortags-link-block {
                clear: both;
                display: inline-block;
                padding: 3px;
                width: 100%;
            }

            .nav-header {
                float: left;
            }
        </style>
    <?php
    }

    public function tt_do_query($sql)
    {
        $db = JFactory::getDBO();
        $db->setQuery($sql);
        return $db->query();
    }

    public function postflight($type, $parent)
    {
        $version = new JVersion;
        $ver = $version->getShortVersion();
        $ver = (int)substr($ver, 0, strpos($ver, '.'));

        $db = JFactory::getDBO();

        /* VER 3.0.0 */

        //+++ CREATE TABLES FOR CATEGORIES +++\\
        $this->tt_do_query("CREATE TABLE IF NOT EXISTS `#__tortags_categories`(`id` int(10) unsigned NOT NULL AUTO_INCREMENT,`catname` varchar(255) NOT NULL DEFAULT '',`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',`published` tinyint(3) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8;");
        $this->tt_do_query("CREATE TABLE IF NOT EXISTS `#__tortags_cat_xref`(`id` int(10) unsigned NOT NULL AUTO_INCREMENT,`cid` int(11) unsigned NOT NULL,`tid` int(11) unsigned NOT NULL, PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8;");

        //+++ GET ROWS NAMES FROM TORETAGS TABLES +++\\
        $db->setQuery("SHOW COLUMNS FROM `#__tortags_tags`");
        $tortags_tags_fields = $db->LoadColumn();

        $db->setQuery("SHOW COLUMNS FROM `#__tortags`");
        $tortags_fields = $db->LoadColumn();

        $db->setQuery("SHOW COLUMNS FROM `#__tortags_components`");
        $tortags_components_fields = $db->LoadColumn();

        $db->setQuery("SHOW COLUMNS FROM `#__tortags_categories`");
        $tortags_cats_fields = $db->LoadColumn();

        if (!in_array('description', $tortags_cats_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_categories` ADD `description` TEXT NOT NULL");
        }
        //+++ ADD FIELDS FOR TABLE TORTAGS_TAGS +++\\

        if (!in_array('alias', $tortags_tags_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_tags` ADD `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'alias for tag' AFTER `title` ");
        }
        if (!in_array('language', $tortags_tags_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_tags` ADD `language` CHAR(7) NOT NULL DEFAULT '*' COMMENT 'The language code for the tag'");
        }
        if (!in_array('published', $tortags_tags_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_tags` ADD `published` TINYINT(3) NOT NULL DEFAULT '1' COMMENT 'Publish status'");
        }
        if (!in_array('catid', $tortags_tags_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_tags` ADD `catid` VARCHAR(255) NOT NULL COMMENT 'Category id(s)'");
        }
        if (!in_array('description', $tortags_tags_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_tags` ADD `description` TEXT NOT NULL COMMENT 'tag description' ");
        }

        //+++ ADD FIELDS FOR TABLE TORTAGS_COMPONENTS +++\\		
        if (!in_array('state_field', $tortags_components_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_components` ADD `state_field` VARCHAR( 150 ) NOT NULL COMMENT 'State field' ");
        }
        if (!in_array('state_status', $tortags_components_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_components` ADD `state_status` VARCHAR( 150 ) NOT NULL COMMENT 'State status value' ");
        }
        if (!in_array('be_key', $tortags_components_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_components` ADD `be_key` VARCHAR( 100 ) NOT NULL COMMENT 'Back End key' ");
        }
        if (!in_array('fe_key', $tortags_components_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_components` ADD `fe_key` VARCHAR( 100 ) NOT NULL COMMENT 'Front End key' ");
        }
        if (!in_array('where_enable', $tortags_components_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_components` ADD `where_enable` TINYINT(3) unsigned NOT NULL DEFAULT '0' COMMENT 'where enable adding' ");
        }
        if (!in_array('def_lang', $tortags_components_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_components` ADD `def_lang` VARCHAR( 20 ) NOT NULL COMMENT 'language for component' ");
        }

        /* VER 2.5.0 */
        if (!in_array('hits', $tortags_tags_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_tags` ADD `hits` INT UNSIGNED NOT NULL COMMENT 'Hits' ");
        }
        if (!in_array('id_field', $tortags_components_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_components` ADD `id_field` VARCHAR( 200 ) NOT NULL DEFAULT 'id' COMMENT 'component id field' AFTER `table` ");
        }
        if (!in_array('exc_views', $tortags_components_fields)) {
            $this->tt_do_query("ALTER TABLE `#__tortags_components` ADD `exc_views` TEXT NOT NULL COMMENT 'Exceptions of views' ");
        }

        $db->setQuery("SELECT `id` FROM `#__tortags_components` WHERE `component`='com_content' ");
        $id = $db->loadResult();
        if (!$id) {
            $this->tt_do_query("INSERT INTO `#__tortags_components` (`id`, `component`, `table`, `title_field`, `description_field`, `created_field`, `url_template`) VALUES
								(NULL, 'com_content', '#__content', 'title', 'introtext', 'created', 'index.php?option=[COMPONENT]&view=article&id=[ID]')");
        }
        $db->setQuery("SELECT `id` FROM `#__tortags_components` WHERE `component`='com_categories' ");
        $id = $db->loadResult();
        if (!$id) {
            $this->tt_do_query("INSERT INTO `#__tortags_components` (`id`, `component`, `table`, `title_field`, `description_field`, `created_field`, `url_template`) VALUES
								(NULL, 'com_categories', '#__categories', 'title', 'description', 'created_time', 'index.php?option=com_content&view=category&layout=blog&id=[ID]')");
        }
        /**/

        /*VER 3.2.2*/
        $obj_indexes = $db->setQuery("SHOW INDEX FROM #__tortags")->loadObjectlist();
        $indexes = [];
        if (sizeof($obj_indexes)) {
            foreach ($obj_indexes as $ind) {
                $indexes[] = $ind->Key_name;
            }
        }

        $obj_tags_indexes = $db->setQuery("SHOW INDEX FROM #__tortags_tags")->loadObjectlist();
        $indexes_tags = [];
        if (sizeof($obj_tags_indexes)) {
            foreach ($obj_tags_indexes as $ind) {
                $indexes_tags[] = $ind->Key_name;
            }
        }

        $obj_components_indexes = $db->setQuery("SHOW INDEX FROM #__tortags_components")->loadObjectlist();
        $indexes_components = [];
        if (sizeof($obj_components_indexes)) {
            foreach ($obj_components_indexes as $ind) {
                $indexes_components[] = $ind->Key_name;
            }
        }

        /*VER 1.3.7*/
        if (!in_array('super_tags_idx', $indexes)) {
            $db->setQuery("ALTER TABLE `#__tortags` ADD INDEX `super_tags_idx` (`tid`, `oid`, `item_id`) COMMENT 'Super tags index';");
            $db->query();
        }
        if (!in_array('tagid_idx', $indexes)) {
            $db->setQuery("ALTER TABLE `#__tortags` ADD INDEX `tagid_idx` (`tid`) COMMENT 'Tag ID index';");
            $db->query();
        }
        if (!in_array('oid_idx', $indexes)) {
            $db->setQuery("ALTER TABLE `#__tortags` ADD INDEX `oid_idx` (`oid`) COMMENT 'Component ID index';");
            $db->query();
        }
        if (!in_array('itemid_idx', $indexes)) {
            $db->setQuery("ALTER TABLE `#__tortags` ADD INDEX `itemid_idx` (`item_id`) COMMENT 'Item ID index';");
            $db->query();
        }

        //for tags
        if (!in_array('tag_title_idx', $indexes_tags)) {
            $db->setQuery("ALTER TABLE `#__tortags_tags` ADD INDEX `tag_title_idx` (`title`) COMMENT 'Title index';");
            $db->query();
        }
        if (!in_array('tag_title_plus_pulished_idx', $indexes_tags)) {
            $db->setQuery("ALTER TABLE `#__tortags_tags` ADD INDEX `tag_title_plus_pulished_idx` (`title`,`published`) COMMENT 'Title plus published index';");
            $db->query();
        }
        if (!in_array('hits_idx', $indexes_tags)) {
            $db->setQuery("ALTER TABLE `#__tortags_tags` ADD INDEX `hits_idx` (`hits`) COMMENT 'Hits index';");
            $db->query();
        }

        //for components
        if (!in_array('component_idx', $indexes_components)) {
            $db->setQuery("ALTER TABLE `#__tortags_components` ADD INDEX `component_idx` (`component`) COMMENT 'Component index';");
            $db->query();
        }

        //test data
        /*
        for ($i=0; $i<25000; $i++)
        {
            $db->setQuery("INSERT INTO `#__tortags_tags` (`id`, `title`, `published`, `hits`) VALUES
								(NULL, 'test_tag_".($i+1)."', '".rand(0,1)."', '".rand(0,10000)."')");
            $db->query();
            $db->setQuery("INSERT INTO `#__tortags` (`id`, `tid`, `oid`, `item_id`) VALUES
								(NULL, '".($i+1)."', 1, ".rand(1,5).")");
            $db->query();
        }*/
        /**/

        ?>
        <br/><br/>
        <div style="padding:20px;">
            <div class="tortags-info-left tortags-info-txt">
                <?php echo JText::_('COM_TORTAGS_DESCRIPTION'); ?>
            </div>
            <br/>

            <div class="tortags-link-block">
                <div class="panel">
                    <div id="cpanel">
                        <div class="<?php echo(($ver != 3) ? 'icon-wrapper' : 'nav-header'); ?>">
                            <div class="icon">
                                <a href="<?php echo JURI::root(); ?>administrator/index.php?option=com_tortags&view=components">
                                    <img alt=""
                                         src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/components48.png">
                                    <span><?php echo JText::_('COM_TORTAGS_MENU_COMOPONENTS'); ?></span></a>
                            </div>
                        </div>
                        <div class="<?php echo(($ver != 3) ? 'icon-wrapper' : 'nav-header'); ?>">
                            <div class="icon">
                                <a href="<?php echo JURI::root(); ?>administrator/index.php?option=com_tortags&view=tags">
                                    <img
                                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/tags48.png"/>
                                    <span><?php echo JText::_('COM_TORTAGS_MENU_TAGS'); ?></span></a>
                            </div>
                        </div>
                        <div class="<?php echo(($ver != 3) ? 'icon-wrapper' : 'nav-header'); ?>">
                            <div class="icon">
                                <a href="<?php echo JURI::root(); ?>administrator/index.php?option=com_tortags&view=impexp">
                                    <img
                                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/import48.png"/>
                                    <span><?php echo JText::_('COM_TORTAGS_MENU_IMPEX'); ?></span></a>
                            </div>
                        </div>
                        <div class="<?php echo(($ver != 3) ? 'icon-wrapper' : 'nav-header'); ?>">
                            <div class="icon">
                                <a href="<?php echo JURI::root(); ?>administrator/index.php?option=com_tortags&view=settings">
                                    <img
                                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/settings48.png"/>
                                    <span><?php echo JText::_('COM_TORTAGS_MENU_SETTINGS'); ?></span></a>
                            </div>
                        </div>
                        <div class="<?php echo(($ver != 3) ? 'icon-wrapper' : 'nav-header'); ?>">
                            <div class="icon">
                                <a href="<?php echo JURI::root(); ?>administrator/index.php?option=com_tortags">
                                    <img
                                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/about48.png"/>
                                    <span><?php echo JText::_('COM_TORTAGS_MENU_ABOUT'); ?></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tortags-info tortags-info-changelog">
                <?php
                jimport('joomla.filesystem.file');
                $file = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_tortags' . DS . 'changelog.txt';
                if (file_exists($file)) {
                    $content = JFile::read($file);
                    echo '<pre>' . $content . '</pre>';
                }
                ?>
                <div class="tortags-info-right tortags-info-txt"><img
                        src="<?php echo JURI::root(); ?>administrator/components/com_tortags/assets/images/belarus.png"/>
                    Made in Belarus [Зроблена ў Беларусі]
                </div>
            </div>
        </div>
    <?php
    }

}
