<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('TZ_Portfolio_PlusHelperAddon_Datas', COM_TZ_PORTFOLIO_PLUS_ADMIN_HELPERS_PATH
    .DIRECTORY_SEPARATOR.'addon_datas.php');

class TZ_Portfolio_PlusViewAddon extends JViewLegacy
{
    protected $item;
    protected $addonItem;
    protected $form;
    protected $state;
    protected $addonItems;

    public function display($tpl = null)
    {
        $this->state        = $this->get('State');
        $this->item         = $this->get('Item');

        if($this -> getLayout() == 'manager') {
            $this->addonItem = $this->get('AddonItem');
        }

        $this -> form       = $this -> get('form');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        if($this -> getLayout() == 'upload') {
            TZ_Portfolio_PlusHelper::addSubmenu('addons');
            $this->sidebar = JHtmlSidebar::render();
        }

        $this->addToolbar();

        parent::display($tpl); // TODO: Change the autogenerated stub
    }

    protected function addToolbar(){
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user		= JFactory::getUser();
        $userId		= $user->get('id');

        $bar    = JToolBar::getInstance();

        $canDo = JHelperContent::getActions('com_tz_portfolio_plus');
        $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

        $text   = JText::_($this->item->name);

        if($this -> getLayout() == 'upload'){
            $text   = JText::_('COM_TZ_PORTFOLIO_PLUS_UPLOAD_AND_INSTALL_ADDON');
        }
        if($this -> getLayout() == 'manager'){
            $text   = JText::_($this->item->name).': Manager Data';
        }

        JToolBarHelper::title(JText::sprintf('COM_TZ_PORTFOLIO_PLUS_ADDONS_MANAGER_TASK',$text),'puzzle');

        if($this -> getLayout() == 'edit'){
            // If not checked out, can save the item.
            if (!$checkedOut) {
                if ($canDo->get('core.edit')) {
                    JToolBarHelper::apply('addon.apply');
                    JToolBarHelper::save('addon.save');
                }
            }
        }

        if($this -> getLayout() == 'edit' && $this -> item -> data_manager){
            JToolbarHelper::link(JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($this -> item -> id)),
                JText::_('COM_TZ_PORTFOLIO_PLUS_ADDON_DATA_MANAGER'),'book');
        }

        if($this -> getLayout() != 'manager') {
            JToolBarHelper::cancel('addon.cancel', JText::_('JTOOLBAR_CLOSE'));

            JToolBarHelper::divider();
        }else{
            JToolbarHelper::custom('addon.cancel','puzzle','', JText::_('COM_TZ_PORTFOLIO_PLUS_ADDONS_MANAGER'), false);
        }

        JToolBarHelper::help('JHELP_CONTENT_ARTICLE_MANAGER',false,'http://wiki.templaza.com/TZ_Portfolio_Plus_v3:Administration#How_to_Add_or_Edit_3');

        // Special HTML workaround to get send popup working
        $docClass       = ' class="btn btn-small"';
        $youtubeIcon    = '<i class="tz-icon-youtube tz-icon-14"></i>&nbsp;';
        $wikiIcon       = '<i class="tz-icon-wikipedia tz-icon-14"></i>&nbsp;';

        $youtubeTitle   = JText::_('COM_TZ_PORTFOLIO_PLUS_VIDEO_TUTORIALS');
        $wikiTitle      = JText::_('COM_TZ_PORTFOLIO_PLUS_WIKIPEDIA_TUTORIALS');

        $videoTutorial    ='<a'.$docClass.' onclick="Joomla.popupWindow(\'http://www.youtube.com/channel/UCykS6SX6L2GOI-n3IOPfTVQ/videos\', \''
            .$youtubeTitle.'\', 800, 500, 1)"'.' href="#">'
            .$youtubeIcon.$youtubeTitle.'</a>';

        $wikiTutorial    ='<a'.$docClass.' onclick="Joomla.popupWindow(\'http://wiki.templaza.com/Main_Page\', \''
            .$wikiTitle.'\', 800, 500, 1)"'.' href="#">'
            .$wikiIcon
            .$wikiTitle.'</a>';

        $bar->appendButton('Custom',$videoTutorial,'youtube');
        $bar->appendButton('Custom',$wikiTutorial,'wikipedia');
    }
}