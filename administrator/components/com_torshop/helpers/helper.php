<?php
defined('_JEXEC') or die;

class TorShopHelper
{

    public static function addSubmenu($submenu)
    {
        JHtmlSidebar::addEntry(JText::_('COM_TORSHOP_SUBMENU_CPANEL'), 'index.php?option=com_torshop&view=torshop', $submenu == 'torshop');
        JHtmlSidebar::addEntry(JText::_('COM_TORSHOP_SUBMENU_PRODUCTS'), 'index.php?option=com_torshop&view=products', $submenu == 'products');
        JHtmlSidebar::addEntry(JText::_('COM_TORSHOP_SUBMENU_ORDERS'), 'index.php?option=com_torshop&view=orders', $submenu == 'orders');
        JHtmlSidebar::addEntry(JText::_('COM_TORSHOP_SUBMENU_CATEGORIES'), 'index.php?option=com_torshop&view=cats', $submenu == 'cats');
        JHtmlSidebar::addEntry(JText::_('COM_TORSHOP_SUBMENU_CURRENCIES'), 'index.php?option=com_torshop&view=currencies', $submenu == 'currencies');
        JHtmlSidebar::addEntry(JText::_('COM_TORSHOP_SUBMENU_SETTINGS'), 'index.php?option=com_torshop&view=settings', $submenu == 'settings');
        return JHtmlSidebar::render();
    }

    public static function getVersion()
    {
        $params = self::getManifest();
        return $params->version;
    }

    public static function getManifest()
    {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT `manifest_cache` FROM #__extensions WHERE element="com_torshop"');
        $params = json_decode($db->loadResult());
        return $params;
    }

    public static function getTTCaption($title = '', $desc = '')
    {
        $title = JText::_($title);
        $desc = JText::_($desc);
        return JHTML::tooltip($desc, $title, 'tooltip.png', $title);
    }

    /*     * *SEF** */

    static public function stringMainURLSafe($string)
    {
        $tortags_settings = &JComponentHelper::getParams('com_torshop');
        $symbol = $tortags_settings->get('alias_replace_symbol', '_');

        if (JFactory::getConfig()->get('unicodeslugs') == 1) {
            $output = TorTagsHelper::stringURLUnicodeSlug($string, $symbol);
        } else {
            $output = TorTagsHelper::stringURLSafe($string, $symbol);
        }
        if (!$output) $output = date('Y_m_d_his');
        return $output;
    }

    public static function replaceSpecSymbInTag($tag = '', $way = 'enc')
    {
        $gsettings = JComponentHelper::getParams('com_torshop');
        $tt_specs = $gsettings->get('url_spec_symbols', '');
        $tt_replcs = $gsettings->get('url_spec_replacers', '');

        $a = $b = array();
        $a[] = '+';
        $b[] = '{plus}';
        $a[] = '@';
        $b[] = '{at}';
        $a[] = '#';
        $b[] = '{sharp}';
        $a[] = '&';
        $b[] = '{and}';
        $a[] = '?';
        $b[] = '{question}';
        $a[] = '%';
        $b[] = '{percent}';
        $a[] = ':';
        $b[] = '{colon}';
        $a[] = '/';
        $b[] = '{slash}';
        if ($tt_specs && $tt_replcs) {
            $tt_specs = explode(',', $tt_specs);
            $tt_replcs = explode(',', $tt_replcs);
            if (sizeof($tt_specs) && sizeof($tt_replcs)) {
                for ($i = 0; $i < count($tt_specs); $i++) {
                    if (isset($tt_replcs[$i])) {
                        $a[] = $tt_specs[$i];
                        $b[] = $tt_replcs[$i];
                    }
                }
            }
        }

        if ($way == 'enc') {
            return str_replace($a, $b, $tag);
        } else {
            return str_replace($b, $a, $tag);
        }
    }

    public static function stringURLSafe($string, $symbol = "_")
    {
        // remove any $symbol from the string since they will be used as concatenaters
        $str = str_replace($symbol, ' ', $string);
        $str = TorTagsHelper::replaceSpecSymbInTag($str);
        $lang = JFactory::getLanguage();
        $str = $lang->transliterate($str);

        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(JString::strtolower($str));

        // Remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', $symbol, $str);

        // Trim dashes at beginning and end of alias
        $str = trim($str, $symbol);

        return $str;
    }

    public static function stringURLUnicodeSlug($string, $symbol = "_")
    {
        // Replace double byte whitespaces by single byte (East Asian languages)
        $str = TorTagsHelper::replaceSpecSymbInTag($string);
        $str = preg_replace('/\xE3\x80\x80/', ' ', $str);

        // Remove any '-' from the string as they will be used as concatenator.
        // Would be great to let the spaces in but only Firefox is friendly with this

        $str = str_replace($symbol, ' ', $str);

        // Replace forbidden characters by whitespaces
        $str = preg_replace('#[:\#\*"@+=;!><&\.%()\]\/\'\\\\|\[]#', "\x20", $str);

        // Delete all '?'
        $str = str_replace('?', '', $str);

        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(JString::strtolower($str));

        // Remove any duplicate whitespace and replace whitespaces by hyphens
        $str = preg_replace('#\x20+#', $symbol, $str);

        return $str;
    }

    /*     * ** */
}
