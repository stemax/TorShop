<?php
/**
 * TorTags component for Joomla 1.6, Joomla 1.7, Joomla 2.5, Joomla 3.x
 * @package TorTags
 * @author Tormix Team
 * @Copyright Copyright (C) Tormix, www.tormix.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class TorTagsControllerSettings extends JControllerForm
{

    protected function allowEdit($data = array(), $key = 'id')
    {
        if ($user->authorise('core.manage', 'com_tortags')) {
            return true;
        }
    }

    public function Cancel()
    {
        $this->setRedirect('index.php?option=com_tortags', false);
    }

    function editcss()
    {
        jimport('joomla.filesystem.file');
        $file = JRequest::getWord('f');
        $file_path = JPATH_SITE . '/components/com_tortags/assets/css/' . $file . '.css';
        if (file_exists($file_path))
            if (is_writeable($file_path)) {
                $editor = &JFactory::getEditor('codemirror');
                $params = array('smilies' => '0',
                    'style' => '0',
                    'layer' => '0',
                    'table' => '0',
                    'buttons' => 'no',
                    'class' => 'template-editor',
                    'clear_entities' => '0',
                    ' editor=>' => 'codemirror',
                    'filter' => 'raw'
                );
                ob_start();
                ?>    Joomla.submitbutton = function(task)
                {
                <?php echo $editor->save('Array'); ?>
                Joomla.submitform(task, document.getElementById('adminForm'));
                }
                <?php
                $js = ob_get_contents();
                ob_get_clean();
                $document = &JFactory::getDocument();
                $document->addScriptDeclaration($js);
                ?>
                <form action="<?php echo JRoute::_('index.php?option=com_tortags&task=settings.editcss'); ?>"
                      method="post" name="adminForm" id="adminForm" class="form-validate">
                    <?php
                    echo '<div>' . JText::_('COM_TORTAGS_TEMPLATE_CSSFILE') . ': ' . $file_path . '</div>';
                    echo $editor->display('css', JFile::read($file_path), '450', '350', '60', '20', false, $params);
                    ?>
                    <div>
                        <input type="button" class="button" name="save"
                               value="<?php echo JText::_('COM_TORTAGS_TEMPLATE_SAVECSS'); ?>"
                               onclick="javascript:Joomla.submitbutton('settings.csssave');"/>
                        <input type="hidden" name="task" value=""/>
                        <input type="hidden" name="f" value="<?php echo $file; ?>"/>
                        <?php echo JHtml::_('form.token'); ?>
                    </div>
                </form>
            <?php
            } else
                echo JText::_('COM_TORTAGS_TEMPLATE_UNWRITEABLE');
    }

    function csssave()
    {
        jimport('joomla.filesystem.file');

        $file = JRequest::getWord('f');
        $file_path = JPATH_SITE . '/components/com_tortags/assets/css/' . $file . '.css';
        if (is_writeable($file_path)) {
            $content = JRequest::getVar('css');
            if (JFile::write($file_path, $content)) {
                $this->setMessage(JText::_('COM_TORTAGS_TEMPLATE_SAVESUCCESS'));
            } else {
                $this->setMessage('Failed to open file for writing!');
            }
        } else {
            $this->setMessage(JText::_('COM_TORTAGS_TEMPLATE_UNWRITEABLE'));
        }
        $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false));
    }

}

        